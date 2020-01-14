<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Package;

use Nusje2000\DependencyGraph\Author\AuthorCollection;
use Nusje2000\DependencyGraph\Dependency\DependencyCollection;
use Nusje2000\DependencyGraph\Dependency\DependencyInterface;
use Nusje2000\DependencyGraph\Replace\ReplaceCollection;

final class Package implements PackageInterface
{
    /**
     * @var bool
     */
    protected $isVendor;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $packageLocation;

    /**
     * @var DependencyCollection
     */
    private $dependencies;

    /**
     * @var AuthorCollection
     */
    private $authors;

    /**
     * @var ReplaceCollection
     */
    private $replaces;

    public function __construct(
        string $name,
        string $packageLocation,
        bool $isFromVendor,
        ?DependencyCollection $dependencies = null,
        ?AuthorCollection $authors = null,
        ?ReplaceCollection $replaces = null
    ) {
        $this->name = $name;
        $this->packageLocation = $packageLocation;
        $this->isVendor = $isFromVendor;
        $this->dependencies = $dependencies ?? new DependencyCollection();
        $this->authors = $authors ?? new AuthorCollection();
        $this->replaces = $replaces ?? new ReplaceCollection();
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getPackageLocation(): string
    {
        return $this->packageLocation;
    }

    /**
     * @inheritDoc
     */
    public function isFromVendor(): bool
    {
        return $this->isVendor;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies(): DependencyCollection
    {
        return $this->dependencies;
    }

    /**
     * @inheritDoc
     */
    public function getDependency(string $name): DependencyInterface
    {
        return $this->dependencies->getDependencyByName($name);
    }

    /**
     * @inheritDoc
     */
    public function hasDependency(string $name): bool
    {
        return $this->dependencies->hasDependencyByName($name);
    }

    public function getAuthors(): AuthorCollection
    {
        return $this->authors;
    }

    public function getReplaces(): ReplaceCollection
    {
        return $this->replaces;
    }
}
