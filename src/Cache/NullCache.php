<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Cache;

use Nusje2000\DependencyGraph\DependencyGraph;
use Nusje2000\DependencyGraph\Exception\CacheException;

final class NullCache implements CacheInterface
{
    /**
     * @inheritDoc
     */
    public function save(DependencyGraph $graph): void
    {
    }

    /**
     * @inheritDoc
     */
    public function load(string $rootPath): DependencyGraph
    {
        throw new CacheException('Loading from null cache is not allowed.');
    }

    /**
     * @inheritDoc
     */
    public function exists(string $rootPath): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function remove(string $rootPath): void
    {
    }
}
