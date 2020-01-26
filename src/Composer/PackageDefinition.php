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
     * @return array<string, string>
     */
    public function getDevDependencies(): array
    {
        return $this->definition['require-dev'] ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getReplaces(): array
    {
        return $this->definition['replace'] ?? [];
    }
}
