<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Dependency;

use Aeviiq\Collection\ImmutableObjectCollection;
use ArrayIterator;
use Closure;
use Nusje2000\DependencyGraph\Exception\DependencyException;

/**
 * @phpstan-extends ImmutableObjectCollection<int|string, DependencyInterface>
 * @psalm-extends   ImmutableObjectCollection<int|string, DependencyInterface>
 *
 * @method ArrayIterator|DependencyInterface[] getIterator()
 * @method DependencyInterface|null first()
 * @method DependencyInterface|null last()
 * @method DependencyCollection filter(Closure $closure)
 */
final class DependencyCollection extends ImmutableObjectCollection
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

    public function filterByName(string $name): self
    {
        return $this->filter(static function (DependencyInterface $dependency) use ($name) {
            return $dependency->getName() === $name;
        });
    }

    public function getDependencyByName(string $name): DependencyInterface
    {
        $dependency = $this->filterByName($name)->first();

        if (null === $dependency) {
            throw new DependencyException(sprintf('Could not find dependency %s.', $name));
        }

        return $dependency;
    }

    public function hasDependencyByName(string $name): bool
    {
        return !$this->filterByName($name)->isEmpty();
    }

    protected function allowedInstance(): string
    {
        return DependencyInterface::class;
    }
}
