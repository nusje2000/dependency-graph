<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

use Aeviiq\Collection\ImmutableObjectCollection;
use ArrayIterator;
use Nusje2000\DependencyGraph\Exception\PackageException;

/**
 * @method ArrayIterator|PackageInterface[] getIterator
 * @method PackageInterface|null first
 * @method PackageInterface|null last
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
