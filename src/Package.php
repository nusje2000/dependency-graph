<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

use Aeviiq\Collection\StringCollection;

final class Package implements PackageInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var PackageCollection
     */
    protected $dependencies;

    /**
     * @var StringCollection
     */
    protected $registeredNamespaces;

    public function __construct(string $name, ?StringCollection $registeredNamespaces = null, PackageCollection $dependencies = null)
    {
        $this->name = $name;
        $this->registeredNamespaces = $registeredNamespaces ?? new StringCollection();
        $this->dependencies = $dependencies ?? new PackageCollection();
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getRegisteredNamespaces(): StringCollection
    {
        return $this->registeredNamespaces;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies(): PackageCollection
    {
        return $this->dependencies->getDepencenciesRecursive();
    }

    /**
     * @inheritDoc
     */
    public function hasDependency(string $name): bool
    {
        return $this->getDependencies()->hasPackageByName($name);
    }

    /**
     * @inheritDoc
     */
    public function getDirectDependencies(): PackageCollection
    {
        return $this->dependencies;
    }

    /**
     * @inheritDoc
     */
    public function hasDirectDependency(string $name): bool
    {
        return $this->getDirectDependencies()->hasPackageByName($name);
    }
}
