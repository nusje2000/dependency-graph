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
        assert(is_array($definition));

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
        $authors = $this->definition['authors'] ?? [];
        assert(is_array($authors));

        return $authors;
    }

    /**
     * @return array<string, string>
     */
    public function getDependencies(): array
    {
        $dependencies = $this->definition['require'] ?? [];
        assert(is_array($dependencies));

        return $dependencies;
    }

    /**
     * @return array<string, string>
     */
    public function getDevDependencies(): array
    {
        $dependencies = $this->definition['require-dev'] ?? [];
        assert(is_array($dependencies));

        return $dependencies;
    }

    /**
     * @return array<string, string>
     */
    public function getReplaces(): array
    {
        $replace = $this->definition['replace'] ?? [];
        assert(is_array($replace));

        return $replace;
    }
}
