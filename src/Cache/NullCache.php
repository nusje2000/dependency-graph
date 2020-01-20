<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Cache;

use Nusje2000\DependencyGraph\DependencyGraph;
use Nusje2000\DependencyGraph\Exception\CacheException;

/**
 * @deprecated The cache component is deprecated since 2.3, will be removed in 3.0.
 */
final class NullCache implements CacheInterface
{
    public function __construct()
    {
        trigger_error('The cache component is deprecated since 2.3, will be removed in 3.0.');
    }

    /**
     * @inheritDoc
     */
    public function save(DependencyGraph $graph): void
    {
        trigger_error('The cache component is deprecated since 2.3, will be removed in 3.0.');
    }

    /**
     * @inheritDoc
     */
    public function load(string $rootPath): DependencyGraph
    {
        trigger_error('The cache component is deprecated since 2.3, will be removed in 3.0.');

        throw new CacheException('Loading from null cache is not allowed.');
    }

    /**
     * @inheritDoc
     */
    public function exists(string $rootPath): bool
    {
        trigger_error('The cache component is deprecated since 2.3, will be removed in 3.0.');

        return false;
    }

    /**
     * @inheritDoc
     */
    public function remove(string $rootPath): void
    {
        trigger_error('The cache component is deprecated since 2.3, will be removed in 3.0.');
    }
}
