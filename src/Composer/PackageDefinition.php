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

    /**
     * @deprecated Will be removed in version 3.0
     */
    public static function createFromDirectory(string $path): self
    {
        trigger_error('This function is deprecated since 2.3, will be removed in 3.0.');

        $pathToComposerFile = $path . DIRECTORY_SEPARATOR . 'composer.json';
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
     * @return array<int, array<string, string>>
     */
    public function getAuthors(): array
    {
        return $this->definition['authors'] ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getDependencies(): array
    {
        return $this->definition['require'] ?? [];
    }

    /**
     * @deprecated Will be removed in version 3.0
     */
    public function hasDependency(string $dependency): bool
    {
        trigger_error('This function is deprecated since 2.3, will be removed in 3.0.');

        return isset($this->definition['require'][$dependency]);
    }

    /**
     * @deprecated Will be removed in version 3.0
     */
    public function setDependency(string $dependency, string $versionConstraint): void
    {
        trigger_error('This function is deprecated since 2.3, will be removed in 3.0.');

        $this->definition['require'][$dependency] = $versionConstraint;
    }

    /**
     * @deprecated Will be removed in version 3.0
     */
    public function removeDependency(string $dependency): void
    {
        trigger_error('This function is deprecated since 2.3, will be removed in 3.0.');

        unset($this->definition['require'][$dependency]);

        if (empty($this->definition['require'])) {
            unset($this->definition['require']);
        }
    }

    /**
     * @deprecated Will be removed in version 3.0
     */
    public function getDependencyVersionConstraint(string $dependency): string
    {
        trigger_error('This function is deprecated since 2.3, will be removed in 3.0.');

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

    /**
     * @deprecated Will be removed in version 3.0
     */
    public function hasDevDependency(string $dependency): bool
    {
        trigger_error('This function is deprecated since 2.3, will be removed in 3.0.');

        return isset($this->definition['require-dev'][$dependency]);
    }

    /**
     * @deprecated Will be removed in version 3.0
     */
    public function setDevDependency(string $dependency, string $versionConstraint): void
    {
        trigger_error('This function is deprecated since 2.3, will be removed in 3.0.');

        $this->definition['require-dev'][$dependency] = $versionConstraint;
    }

    /**
     * @deprecated Will be removed in version 3.0
     */
    public function removeDevDependency(string $dependency): void
    {
        trigger_error('This function is deprecated since 2.3, will be removed in 3.0.');

        unset($this->definition['require-dev'][$dependency]);

        if (empty($this->definition['require-dev'])) {
            unset($this->definition['require-dev']);
        }
    }

    /**
     * @deprecated Will be removed in version 3.0
     */
    public function getDevDependencyVersionConstraint(string $dependency): string
    {
        trigger_error('This function is deprecated since 2.3, will be removed in 3.0.');

        $constraint = $this->definition['require-dev'][$dependency] ?? null;

        if (!is_string($constraint)) {
            throw new DefinitionException(sprintf('Could not get version constraint for dev dependency "%s".', $dependency));
        }

        return $constraint;
    }

    /**
     * @return array<string, string>
     */
    public function getReplaces(): array
    {
        return $this->definition['replace'] ?? [];
    }

    /**
     * @deprecated Will be removed in version 3.0
     */
    public function setReplace(string $name, string $version): void
    {
        trigger_error('This function is deprecated since 2.3, will be removed in 3.0.');

        $this->definition['replace'][$name] = $version;
    }

    /**
     * @deprecated Will be removed in version 3.0
     */
    public function removeReplace(string $name): void
    {
        trigger_error('This function is deprecated since 2.3, will be removed in 3.0.');

        if (isset($this->definition['replace'][$name])) {
            unset($this->definition['replace'][$name]);
        }
    }

    /**
     * @deprecated Will be removed in version 3.0
     */
    public function save(): void
    {
        trigger_error('This function is deprecated since 2.3, will be removed in 3.0.');

        $encoded = json_encode($this->definition, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if (!is_string($encoded)) {
            throw new DefinitionException(sprintf('Could not encode definition due to "%s".', json_last_error_msg()));
        }

        file_put_contents($this->getPackageDirectory() . DIRECTORY_SEPARATOR . 'composer.json', $encoded);
    }
}
