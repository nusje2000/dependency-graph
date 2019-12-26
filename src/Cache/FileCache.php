<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Cache;

use Nusje2000\DependencyGraph\DependencyGraph;

final class FileCache implements CacheInterface
{
    /**
     * @inheritDoc
     */
    public function save(DependencyGraph $graph): void
    {
        // TODO: Implement save() method.
    }

    /**
     * @inheritDoc
     */
    public function load(string $rootPath): DependencyGraph
    {
        // TODO: Implement load() method.
    }

    /**
     * @inheritDoc
     */
    public function exists(string $rootPath): bool
    {
        // TODO: Implement exists() method.
    }
}
