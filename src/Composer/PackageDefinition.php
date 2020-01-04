<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Composer;

use Nusje2000\DependencyGraph\Exception\DefinitionException;
use Symfony\Component\Finder\SplFileInfo;

final class PackageDefinition
{
    /**
     * @var array<mixed, mixed>
     */
    protected $definition;

    /**
     * @var string
     */
    protected $source;

    /**
     * @param array<mixed, mixed> $definition
     */
    public function __construct(array $definition, string $source)
    {
        $this->definition = $definition;
        $this->source = $source;
    }

    public static function createFromFile(SplFileInfo $file): self
    {
        $definition = json_decode($file->getContents(), true);

        return new PackageDefinition($definition, $file->getPath());
    }

    public static function createFromDirectory(string $path): self
    {
        $pathToComposerFile = $path . DIRECTORY_SEPARATOR . '/composer.json';
        $contents = file_get_contents($pathToComposerFile);

        if (!is_string($contents)) {
            throw new DefinitionException(sprintf('Could not read contents of file "%s".', $pathToComposerFile));
        }

        $definition = json_decode($contents, true);

        return new PackageDefinition($definition, $path);
    }

    public function getName(): string
    {
        $name = $this->definition['name'] ?? null;

        if (!is_string($name)) {
            throw DefinitionException::missingNameDefinition($this->getPackageDirectory() . DIRECTORY_SEPARATOR . 'composer.json');
        }

        return $name;
    }

    public function getPackageDirectory(): string
    {
        return $this->source;
    }

    /**
     * @return array<string, string>
     */
    public function getDependencies(): array
    {
        return $this->definition['require'] ?? [];
    }

    public function hasDependency(string $dependency): bool
    {
        return isset($this->definition['require'][$dependency]);
    }

    public function setDependency(string $dependency, string $versionConstraint): void
    {
        $this->definition['require'][$dependency] = $versionConstraint;
    }

    public function removeDependency(string $dependency): void
    {
        unset($this->definition['require'][$dependency]);

        if (empty($this->definition['require'])) {
            unset($this->definition['require']);
        }
    }

    public function getDependencyVersionConstraint(string $dependency): string
    {
        $constraint = $this->definition['require'][$dependency] ?? null;

        if (!is_string($constraint)) {
            throw new DefinitionException(sprintf('Could not get version constraint for dependency "%s".', $dependency));
        }

        return $constraint;
    }

    /**
     * @return array<string, string>
     */
    public function getDevDependencies(): array
    {
        return $this->definition['require-dev'] ?? [];
    }

    public function hasDevDependency(string $dependency): bool
    {
        return isset($this->definition['require-dev'][$dependency]);
    }

    public function addDevDependency(string $dependency, string $versionConstraint): void
    {
        $this->definition['require-dev'][$dependency] = $versionConstraint;
    }

    public function removeDevDependency(string $dependency): void
    {
        unset($this->definition['require-dev'][$dependency]);

        if (empty($this->definition['require-dev'])) {
            unset($this->definition['require-dev']);
        }
    }

    public function getDevDependencyVersionConstraint(string $dependency): string
    {
        $constraint = $this->definition['require-dev'][$dependency] ?? null;

        if (!is_string($constraint)) {
            throw new DefinitionException(sprintf('Could not get version constraint for dev dependency "%s".', $dependency));
        }

        return $constraint;
    }

    public function save(): void
    {
        $encoded = json_encode($this->definition, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if (!is_string($encoded)) {
            throw new DefinitionException(sprintf('Could not encode definition due to "%s".', json_last_error_msg()));
        }

        file_put_contents($this->getPackageDirectory() . DIRECTORY_SEPARATOR . 'composer.json', $encoded);
    }
}
