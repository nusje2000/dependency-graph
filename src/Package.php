<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

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

    public function __construct(string $name, string $packageLocation, bool $isFromVendor, ?DependencyCollection $dependencies = null)
    {
        $this->name = $name;
        $this->dependencies = $dependencies ?? new DependencyCollection();
        $this->packageLocation = $packageLocation;
        $this->isVendor = $isFromVendor;
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
    public function hasDependency(string $name): bool
    {
        return $this->dependencies->hasDependency($name);
    }
}
