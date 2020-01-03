<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Builder;

use Nusje2000\DependencyGraph\Composer\PackageDefinition;
use Nusje2000\DependencyGraph\Dependency;
use Nusje2000\DependencyGraph\DependencyCollection;
use Nusje2000\DependencyGraph\DependencyGraph;
use Nusje2000\DependencyGraph\DependencyTypeEnum;
use Nusje2000\DependencyGraph\Exception\DefinitionException;
use Nusje2000\DependencyGraph\Package;
use Nusje2000\DependencyGraph\PackageCollection;
use Nusje2000\DependencyGraph\PackageInterface;
use Symfony\Component\Finder\Finder;

final class Builder implements GraphBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function build(string $rootPath): DependencyGraph
    {
        $definitions = $this->getComposerDefinitions($rootPath);
        $packages = array_map(function (PackageDefinition $definition) {
            return $this->createPackage($definition);
        }, $definitions);

        return new DependencyGraph($rootPath, new PackageCollection($packages));
    }

    /**
     * @return array<string, PackageDefinition>
     */
    private function getComposerDefinitions(string $rootPath): array
    {
        $finder = Finder::create();
        $finder->in($rootPath);
        $finder->ignoreUnreadableDirs();
        $finder->name('composer.json');
        $finder->files();

        $definitions = [];

        foreach ($finder->getIterator() as $file) {
            try {
                $definition = PackageDefinition::createFromFile($file);
            } catch (DefinitionException $exception) {
                continue;
            }

            $name = $definition->getName();
            if (isset($definitions[$name])) {
                continue;
            }

            $definitions[$name] = $definition;
        }

        return $definitions;
    }

    private function createPackage(PackageDefinition $definition): PackageInterface
    {
        $dependencies = [];
        foreach ($definition->getDependencies() as $dependencyName => $versionConstraint) {
            $type = DependencyTypeEnum::createFromDependencyName($dependencyName);
            $dependencies[] = new Dependency($dependencyName, $versionConstraint, false, $type);
        }

        foreach ($definition->getDevDependencies() as $dependencyName => $versionConstraint) {
            $type = DependencyTypeEnum::createFromDependencyName($dependencyName);
            $dependencies[] = new Dependency($dependencyName, $versionConstraint, true, $type);
        }

        return new Package($definition->getName(), $definition->getPackageDirectory(), new DependencyCollection($dependencies));
    }
}
