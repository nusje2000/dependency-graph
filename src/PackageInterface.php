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
     * Returns all the dependencies of the package
     */
    public function getDependencies(): DependencyCollection;

    /**
     * Checks if a dependency with the given name exists
     */
    public function hasDependency(string $name);
}
