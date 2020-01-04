<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

use Aeviiq\Collection\ImmutableObjectCollection;
use ArrayIterator;
use Closure;
use Nusje2000\DependencyGraph\Exception\PackageException;

/**
 * @method ArrayIterator|PackageInterface[] getIterator
 * @method PackageInterface|null first
 * @method PackageInterface|null last
 * @method PackageCollection filter(Closure $closure)
 */
final class PackageCollection extends ImmutableObjectCollection
{
    public function getDependencies(): DependencyCollection
    {
        return new DependencyCollection(
            array_merge(...array_values($this->map(static function (PackageInterface $package): array {
                return array_values($package->getDependencies()->toArray());
            })))
        );
    }

    /**
     * @throws PackageException
     */
    public function getPackageByName(string $name): PackageInterface
    {
        $package = $this->filter(static function (PackageInterface $dependency) use ($name) {
            return $dependency->getName() === $name;
        })->first();

        if (null === $package) {
            throw PackageException::notFound($name);
        }

        return $package;
    }

    public function filterByDependency(string $dependencyName): PackageCollection
    {
        return $this->filter(static function (PackageInterface $package) use ($dependencyName): bool {
            return $package->hasDependency($dependencyName);
        });
    }

    public function hasPackageByName(string $name): bool
    {
        return !$this->filter(static function (PackageInterface $dependency) use ($name) {
            return $dependency->getName() === $name;
        })->isEmpty();
    }

    protected function allowedInstance(): string
    {
        return PackageInterface::class;
    }
}
