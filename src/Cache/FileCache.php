<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Cache;

use Nusje2000\DependencyGraph\DependencyGraph;
use Nusje2000\DependencyGraph\Exception\CacheException;

/**
 * @deprecated The cache component is deprecated since 2.3, will be removed in 3.0.
 */
final class FileCache implements CacheInterface
{
    /**
     * @inheritDoc
     */
    public function save(DependencyGraph $graph): void
    {
        $serialized = serialize($graph);
        file_put_contents($this->getCacheFileLocation($graph->getRootPath()), $serialized);
    }

    /**
     * @inheritDoc
     */
    public function load(string $rootPath): DependencyGraph
    {
        if (!$this->exists($rootPath)) {
            throw CacheException::notFound($rootPath);
        }

        $serialized = file_get_contents($this->getCacheFileLocation($rootPath));

        if (!is_string($serialized)) {
            throw CacheException::notFound($rootPath);
        }

        $graph = unserialize($serialized);

        if (!$graph instanceof DependencyGraph) {
            throw CacheException::invalidCache($rootPath);
        }

        return $graph;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $rootPath): bool
    {
        return file_exists($this->getCacheFileLocation($rootPath));
    }

    /**
     * @inheritDoc
     */
    public function remove(string $rootPath): void
    {
        if ($this->exists($rootPath)) {
            unlink($this->getCacheFileLocation($rootPath));
        }
    }

    private function getCacheFileLocation(string $rootPath): string
    {
        return $rootPath . DIRECTORY_SEPARATOR . 'dependency-graph.lock';
    }
}
