<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Composer;

use Aeviiq\Collection\StringCollection;
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

        return new PackageDefinition($definition, $file->getPathname());
    }

    public function getName(): string
    {
        $name = $this->definition['name'] ?? null;

        if (!is_string($name)) {
            throw DefinitionException::missingNameDefinition($this->source);
        }

        return $name;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getDependencies(?string $regex = null): StringCollection
    {
        $dependencies = new StringCollection(array_keys($this->definition['require'] ?? []));

        if (null === $regex) {
            return $dependencies;
        }

        return $dependencies->filter(static function (string $package) use ($regex) {
            return 1 === preg_match($regex, $package);
        });
    }

    public function getDevDependencies(?string $regex = null): StringCollection
    {
        $dependencies = new StringCollection(array_keys($this->definition['require-dev'] ?? []));

        if (null === $regex) {
            return $dependencies;
        }

        return $dependencies->filter(static function (string $package) use ($regex) {
            return 1 === preg_match($regex, $package);
        });
    }

    public function getNamespaces(): StringCollection
    {
        $namespaces = $this->definition['autoload']['psr-4'] ?? [];

        return new StringCollection(array_keys($namespaces));
    }

    public function getDevNamespaces(): StringCollection
    {
        $namespaces = $this->definition['autoload-dev']['psr-4'] ?? [];

        return new StringCollection(array_keys($namespaces));
    }
}
