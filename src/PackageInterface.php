<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

use Aeviiq\Collection\StringCollection;

interface PackageInterface
{
    /**
     * Returns the name of this dependency
     */
    public function getName(): string;

    /**
     * Returns the namespaces registered by the package
     */
    public function getRegisteredNamespaces(): StringCollection;

    /**
     * Returns the dependencies of the package (including dependencies of dependencies)
     */
    public function getDependencies(): PackageCollection;

    /**
     * Returns true when there is a depencency with the given name anywhere in the dependency tree
     */
    public function hasDependency(string $name): bool;

    /**
     * Returns the dependencies of the package (only direct dependencies and not including depencencies of dependencies)
     */
    public function getDirectDependencies(): PackageCollection;

    /**
     * Returns true when the package has a dependency with the given name
     * within the first level of dependencies (not iterating through depencencies of depencencies)
     */
    public function hasDirectDependency(string $name): bool;
}
