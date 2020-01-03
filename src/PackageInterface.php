<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

interface PackageInterface
{
    /**
     * Returns the name of this dependency
     */
    public function getName(): string;

    /**
     * Returns the location where the package is defined
     */
    public function getPackageLocation(): string;

    /**
     * Returns all the dependencies of the package
     */
    public function getDependencies(): DependencyCollection;

    /**
     * Checks if a dependency with the given name exists
     */
    public function hasDependency(string $name);
}
