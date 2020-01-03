<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

final class Package implements PackageInterface
{
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

    public function __construct(string $name, string $packageLocation, ?DependencyCollection $dependencies = null)
    {
        $this->name = $name;
        $this->dependencies = $dependencies ?? new DependencyCollection();
        $this->packageLocation = $packageLocation;
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
