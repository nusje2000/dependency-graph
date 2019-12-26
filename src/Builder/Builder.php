<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Builder;

use Aeviiq\Collection\StringCollection;
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

        foreach (array_keys($definitions) as $packageName) {
            $this->registerPackage($packageName, $definitions, $packages);
        }

        return new DependencyGraph($rootPath, $packages);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function getComposerDefinitions(string $rootPath): array
    {
        $finder = Finder::create();
        $finder->in($rootPath);
        $finder->name('composer.json');

        $definitions = [];

        foreach ($finder->getIterator() as $file) {
            $definition = json_decode($file->getContents(), true);
            $name = $definition['name'] ?? null;

            if (null === $name || !is_string($name)) {
                throw DefinitionException::missingNameDefinition($file->getPathname());
            }

            if (isset($definitions[$name])) {
                throw DefinitionException::duplicatePackageDefinition($name);
            }

            $definitions[$name] = $definition;
        }

        return $definitions;
    }

    /**
     * @param array<string, array<string, mixed>> $definition
     */
    private function getNamespacesFromComposerDefinition(array $definition): StringCollection
    {
        $namespaces = array_merge(
            $definition['autoload']['psr-4'] ?? [],
            $definition['autoload-dev']['psr-4'] ?? []
        );

        return new StringCollection(array_keys($namespaces));
    }

    /**
     * @param array<string, array<string, mixed>> $definition
     */
    private function getDependenciesFromComposerDefinition(array $definition): StringCollection
    {
        $dependencies = array_merge(
            $definition['require'] ?? [],
            $definition['require-dev'] ?? []
        );

        $dependencies = array_filter(array_keys($dependencies), static function (string $name) {
            return 1 === preg_match(self::PACKAGE_NAME_REGEX, $name);
        });

        return new StringCollection($dependencies);
    }

    private function registerPackage(string $name, array $definitions, PackageCollection $packageRegistry): void
    {
        $definition = $definitions[$name] ?? [];
        $dependencyNames = $this->getDependenciesFromComposerDefinition($definition);

        $dependencies = new PackageCollection();
        foreach ($dependencyNames as $dependencyName) {
            if (!$packageRegistry->hasPackageByName($dependencyName)) {
                $this->registerPackage($dependencyName, $definitions, $packageRegistry);
            }

            $dependencies->append($packageRegistry->getPackageByName($dependencyName));
        }

        if (!$packageRegistry->hasPackageByName($name)) {
            $namespaces = $this->getNamespacesFromComposerDefinition($definition);

            $package = new Package($name, $namespaces, $dependencies);
            $packageRegistry->append($package);
        }
    }
}
