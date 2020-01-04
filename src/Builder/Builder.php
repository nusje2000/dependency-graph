<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Builder;

use Nusje2000\DependencyGraph\Composer\PackageDefinition;
use Nusje2000\DependencyGraph\Dependency\Dependency;
use Nusje2000\DependencyGraph\Dependency\DependencyCollection;
use Nusje2000\DependencyGraph\Dependency\DependencyTypeEnum;
use Nusje2000\DependencyGraph\DependencyGraph;
use Nusje2000\DependencyGraph\Exception\DefinitionException;
use Nusje2000\DependencyGraph\Package\Package;
use Nusje2000\DependencyGraph\Package\PackageCollection;
use Nusje2000\DependencyGraph\Package\PackageInterface;
use Symfony\Component\Finder\Finder;

final class Builder implements GraphBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function build(string $rootPath): DependencyGraph
    {
        $definitions = $this->getComposerDefinitions($rootPath);
        $packages = array_map(function (PackageDefinition $definition) use ($rootPath) {
            return $this->createPackage($definition, $rootPath);
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
            $definition = PackageDefinition::createFromFile($file);

            try {
                $name = $definition->getName();
            } catch (DefinitionException $exception) {
                continue;
            }

            if (isset($definitions[$name])) {
                continue;
            }

            $definitions[$name] = $definition;
        }

        return $definitions;
    }

    private function createPackage(PackageDefinition $definition, string $rootPath): PackageInterface
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

        $isFromVendor = 0 === strpos($definition->getPackageDirectory(), $rootPath . DIRECTORY_SEPARATOR . 'vendor');

        return new Package($definition->getName(), $definition->getPackageDirectory(), $isFromVendor, new DependencyCollection($dependencies));
    }
}
