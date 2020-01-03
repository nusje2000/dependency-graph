<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

interface DependencyInterface
{
    /**
     * Returns the name of the dependency
     */
    public function getName(): string;

    /**
     * @see https://getcomposer.org/doc/articles/versions.md
     *
     * Returns the allowed version string.
     */
    public function getVersionConstraint(): string;

    /**
     * Return the type of the dependency. i.e. to check if its an extension or a third party package
     */
    public function getType(): DependencyTypeEnum;

    /**
     * Check if a dependency is a dev dependency or not
     */
    public function isDev(): bool;
}
