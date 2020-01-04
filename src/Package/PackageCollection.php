<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Package;

use Aeviiq\Collection\ImmutableObjectCollection;
use ArrayIterator;
use Closure;
use Nusje2000\DependencyGraph\Exception\PackageException;

/**
 * @phpstan-extends ImmutableObjectCollection<int|string, PackageInterface>
 * @psalm-extends   ImmutableObjectCollection<int|string, PackageInterface>
 *
 * @method ArrayIterator|PackageInterface[] getIterator()
 * @method PackageInterface|null first()
 * @method PackageInterface|null last()
 * @method PackageCollection filter(Closure $closure)
 */
final class PackageCollection extends ImmutableObjectCollection
{
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
