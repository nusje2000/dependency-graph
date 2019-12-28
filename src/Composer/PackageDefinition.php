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

    /**
     * @return array<string, string>
     */
    public function getDependencies(): array
    {
        return $this->definition['require'] ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getDevDependencies(): array
    {
        return $this->definition['require-dev'] ?? [];
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
