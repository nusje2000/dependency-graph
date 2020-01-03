<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

use Aeviiq\Collection\ObjectCollection;
use ArrayIterator;
use Nusje2000\DependencyGraph\Exception\DependencyException;

/**
 * @method ArrayIterator|DependencyInterface[] getIterator
 * @method DependencyInterface|null first
 * @method DependencyInterface|null last
 */
final class DependencyCollection extends ObjectCollection
{
    public function filterExtensions(): self
    {
        return $this->filterByType(new DependencyTypeEnum(DependencyTypeEnum::PHP_EXTENSION));
    }

    public function filterPackages(): self
    {
        return $this->filterByType(new DependencyTypeEnum(DependencyTypeEnum::PACKAGE));
    }

    public function filterByType(DependencyTypeEnum $type): self
    {
        return $this->filter(static function (DependencyInterface $dependency) use ($type) {
            return $dependency->getType()->equals($type);
        });
    }

    public function getDependencyByName(string $name): DependencyInterface
    {
        $dependency = $this->filter(static function (DependencyInterface $dependency) use ($name) {
            return $dependency->getName() === $name;
        })->first();

        if (null === $dependency) {
            throw new DependencyException(sprintf('Could not find dependency %s.', $name));
        }

        return $dependency;
    }

    public function hasDependency(string $name): bool
    {
        return !$this->filter(static function (DependencyInterface $dependency) use ($name) {
            return $dependency->getName() === $name;
        })->isEmpty();
    }

    protected function allowedInstance(): string
    {
        return DependencyInterface::class;
    }
}
