<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Package;

use Nusje2000\DependencyGraph\Author\AuthorCollection;
use Nusje2000\DependencyGraph\Dependency\DependencyCollection;
use Nusje2000\DependencyGraph\Dependency\DependencyInterface;
use Nusje2000\DependencyGraph\Replace\ReplaceCollection;

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
     * Returns true when the package is located within the vendor directory
     */
    public function isFromVendor(): bool;

    /**
     * Returns all the dependencies of the package
     */
    public function getDependencies(): DependencyCollection;

    /**
     * Returns the dependency with the given name
     */
    public function getDependency(string $name): DependencyInterface;

    /**
     * Checks if a dependency with the given name exists
     */
    public function hasDependency(string $name): bool;

    /**
     * Returns the authors of the package
     */
    public function getAuthors(): AuthorCollection;

    /**
     * Returns the packages this package replaces
     */
    public function getReplaces(): ReplaceCollection;
}
