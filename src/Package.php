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
     * @var DependencyCollection
     */
    protected $dependencies;

    /**
     * @var StringCollection
     */
    protected $registeredNamespaces;

    public function __construct(string $name, ?StringCollection $registeredNamespaces = null, ?DependencyCollection $dependencies = null)
    {
        $this->name = $name;
        $this->registeredNamespaces = $registeredNamespaces ?? new StringCollection();
        $this->dependencies = $dependencies ?? new DependencyCollection();
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
    public function getRegisteredNamespaces(): StringCollection
    {
        return $this->registeredNamespaces;
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
