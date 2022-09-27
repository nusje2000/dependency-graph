<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Builder;

use Nusje2000\DependencyGraph\Author\Author;
use Nusje2000\DependencyGraph\Author\AuthorCollection;
use Nusje2000\DependencyGraph\Composer\PackageDefinition;
use Nusje2000\DependencyGraph\Dependency\Dependency;
use Nusje2000\DependencyGraph\Dependency\DependencyCollection;
use Nusje2000\DependencyGraph\Dependency\DependencyTypeEnum;
use Nusje2000\DependencyGraph\DependencyGraph;
use Nusje2000\DependencyGraph\Exception\DefinitionException;
use Nusje2000\DependencyGraph\Package\Package;
use Nusje2000\DependencyGraph\Package\PackageCollection;
use Nusje2000\DependencyGraph\Package\PackageInterface;
use Nusje2000\DependencyGraph\Replace\Replace;
use Nusje2000\DependencyGraph\Replace\ReplaceCollection;
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
        $finder->sortByName();
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

        $authors = [];
        foreach ($definition->getAuthors() as $author) {
            if (!isset($author['name'])) {
                continue;
            }

            $authors[] = new Author($author['name'], $author['email'] ?? null, $author['homepage'] ?? null, $author['role'] ?? null);
        }

        $replaces = [];
        foreach ($definition->getReplaces() as $name => $version) {
            $replaces[] = new Replace($name, $version);
        }

        $path = str_replace($rootPath, '', $definition->getPackageDirectory());

        $isFromVendor =
            false !== strpos($path, DIRECTORY_SEPARATOR . 'vendor') ||
            false !== strpos($path, DIRECTORY_SEPARATOR . 'node_modules');

        return new Package(
            $definition->getName(),
            $definition->getPackageDirectory(),
            $isFromVendor,
            new DependencyCollection($dependencies),
            new AuthorCollection($authors),
            new ReplaceCollection($replaces)
        );
    }
}
