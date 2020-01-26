<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

use Nusje2000\DependencyGraph\Builder\Builder;
use Nusje2000\DependencyGraph\Builder\GraphBuilderInterface;
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

    public static function build(string $rootPath, ?GraphBuilderInterface $builder = null): self
    {
        if (null === $builder) {
            $builder = new Builder();
        }

        return $builder->build($rootPath);
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

    public function getSubPackages(): PackageCollection
    {
        $rootPackage = $this->getRootPackage();

        return $this->packages->filter(static function (PackageInterface $package) use ($rootPackage): bool {
            return !$package->isFromVendor() && $package !== $rootPackage;
        });
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
