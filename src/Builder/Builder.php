<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Builder;

use Aeviiq\Collection\StringCollection;
use Nusje2000\DependencyGraph\Composer\PackageDefinition;
use Nusje2000\DependencyGraph\DependencyGraph;
use Nusje2000\DependencyGraph\Exception\DefinitionException;
use Nusje2000\DependencyGraph\Package;
use Nusje2000\DependencyGraph\PackageCollection;
use Symfony\Component\Finder\Finder;

final class Builder implements GraphBuilderInterface
{
    private const PACKAGE_NAME_REGEX = '/[a-z0-9-_]+\/[a-z0-9-_]+/i';

    /**
     * @inheritDoc
     */
    public function build(string $rootPath): DependencyGraph
    {
        $definitions = $this->getComposerDefinitions($rootPath);
        $packages = new PackageCollection();

        foreach ($definitions as $definition) {
            $this->registerPackage($definition, $definitions, $packages);
        }

        return new DependencyGraph($rootPath, $packages);
    }

    /**
     * @return array<string, PackageDefinition>
     */
    private function getComposerDefinitions(string $rootPath): array
    {
        $finder = Finder::create();
        $finder->in($rootPath);
        $finder->name('composer.json');

        $definitions = [];

        foreach ($finder->getIterator() as $file) {
            $definition = PackageDefinition::createFromFile($file);
            $name = $definition->getName();

            if (isset($definitions[$name])) {
                throw DefinitionException::duplicatePackageDefinition($definitions[$name], $definition);
            }

            $definitions[$name] = $definition;
        }

        return $definitions;
    }

    private function getNamespacesFromComposerDefinition(PackageDefinition $definition): StringCollection
    {
        $namespaces = $definition->getNamespaces();
        $namespaces->merge($definition->getDevNamespaces());

        return $namespaces;
    }

    private function getDependenciesFromComposerDefinition(PackageDefinition $definition): StringCollection
    {
        $dependencies = $definition->getDependencies(self::PACKAGE_NAME_REGEX);
        $dependencies->merge($definition->getDevDependencies(self::PACKAGE_NAME_REGEX));

        return $dependencies;
    }

    /**
     * @param array<string, PackageDefinition> $definitions
     */
    private function registerPackage(PackageDefinition $definition, array $definitions, PackageCollection $packageRegistry): void
    {
        $dependencyNames = $this->getDependenciesFromComposerDefinition($definition);

        $dependencies = new PackageCollection();
        foreach ($dependencyNames as $dependencyName) {
            if (!$packageRegistry->hasPackageByName($dependencyName)) {
                if (!isset($definitions[$dependencyName])) {
                    throw DefinitionException::unresolvableReference($dependencyName, $definition);
                }

                $this->registerPackage($definitions[$dependencyName], $definitions, $packageRegistry);
            }

            $dependencies->append($packageRegistry->getPackageByName($dependencyName));
        }

        if (!$packageRegistry->hasPackageByName($definition->getName())) {
            $namespaces = $this->getNamespacesFromComposerDefinition($definition);

            $package = new Package($definition->getName(), $namespaces, $dependencies);
            $packageRegistry->append($package);
        }
    }
}
