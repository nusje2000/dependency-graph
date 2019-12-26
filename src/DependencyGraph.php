<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

use Nusje2000\DependencyGraph\Builder\GraphBuilderInterface;
use Nusje2000\DependencyGraph\Cache\CacheInterface;
use Nusje2000\DependencyGraph\Cache\NullCache;

final class DependencyGraph
{
    /**
     * @var PackageCollection
     */
    protected $packages;

    /**
     * @var string
     */
    protected $rootPath;

    public function __construct(string $rootPath, PackageCollection $packages)
    {
        $this->packages = $packages;
        $this->rootPath = $rootPath;
    }

    public static function build(string $rootPath, GraphBuilderInterface $builder, ?CacheInterface $cache = null): self
    {
        if (null === $cache) {
            $cache = new NullCache();
        }

        if ($cache->exists($rootPath)) {
            return $cache->load($rootPath);
        }

        $graph = $builder->build($rootPath);
        $cache->save($graph);

        return $graph;
    }

    public function getGraph(): PackageCollection
    {
        return $this->packages;
    }

    public function getRootPath(): string
    {
        return $this->rootPath;
    }
}
