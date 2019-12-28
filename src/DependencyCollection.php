<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

use Aeviiq\Collection\ObjectCollection;
use ArrayIterator;

/**
 * @method ArrayIterator|DependencyInterface[] getIterator
 * @method DependencyInterface|null first
 * @method DependencyInterface|null last
 */
final class DependencyCollection extends ObjectCollection
{
    public function filterExtensions(): self
    {
        return $this->filterByType(new DependencyTypeEnum(DependencyTypeEnum::EXTENSION));
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
