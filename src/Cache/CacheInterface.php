<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Cache;

use Nusje2000\DependencyGraph\DependencyGraph;
use Nusje2000\DependencyGraph\Exception\CacheException;

interface CacheInterface
{
    /**
     * Cache the dependency graph
     */
    public function save(DependencyGraph $graph): void;

    /**
     * Load the dependency graph for the given path
     *
     * @throws CacheException
     */
    public function load(string $rootPath): DependencyGraph;

    /**
     * Check if there is a cache item for the given path
     */
    public function exists(string $rootPath): bool;
}
