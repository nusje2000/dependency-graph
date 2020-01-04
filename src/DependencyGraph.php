<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

use Nusje2000\DependencyGraph\Builder\Builder;
use Nusje2000\DependencyGraph\Builder\GraphBuilderInterface;
use Nusje2000\DependencyGraph\Cache\CacheInterface;
use Nusje2000\DependencyGraph\Cache\NullCache;
use Nusje2000\DependencyGraph\Exception\PackageException;
use Nusje2000\DependencyGraph\Package\PackageCollection;
use Nusje2000\DependencyGraph\Package\PackageInterface;

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

    public static function build(string $rootPath, ?GraphBuilderInterface $builder = null, ?CacheInterface $cache = null): self
    {
        if (null === $cache) {
            $cache = new NullCache();
        }

        if (null === $builder) {
            $builder = new Builder();
        }

        if ($cache->exists($rootPath)) {
            return $cache->load($rootPath);
        }

        $graph = $builder->build($rootPath);
        $cache->save($graph);

        return $graph;
    }

    public function getPackages(): PackageCollection
    {
        return $this->packages;
    }

    public function getRootPackage(): PackageInterface
    {
        $rootPackage = $this->packages->filter(function (PackageInterface $package) {
            return $package->getPackageLocation() === $this->getRootPath();
        })->first();

        if (null === $rootPackage) {
            throw new PackageException(sprintf('Could not find root package (searched in "%s").', $this->getRootPath()));
        }

        return $rootPackage;
    }

    public function getPackage(string $packageName): PackageInterface
    {
        return $this->packages->getPackageByName($packageName);
    }

    public function hasPackage(string $packageName): bool
    {
        return $this->packages->hasPackageByName($packageName);
    }

    public function getRootPath(): string
    {
        return $this->rootPath;
    }
}
