<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Replace;

use Aeviiq\Collection\ObjectCollection;
use ArrayIterator;
use Closure;
use Nusje2000\DependencyGraph\Exception\DependencyException;

/**
 * @phpstan-extends ImmutableObjectCollection<int|string, ReplaceInterface>
 * @psalm-extends   ImmutableObjectCollection<int|string, ReplaceInterface>
 *
 * @method ArrayIterator|ReplaceInterface[] getIterator()
 * @method ReplaceInterface|null first()
 * @method ReplaceInterface|null last()
 * @method ReplaceCollection filter(Closure $closure)
 */
final class ReplaceCollection extends ObjectCollection
{
    public function getReplaceByName(string $name): ReplaceInterface
    {
        $replace = $this->filterByName($name)->first();

        if (null === $replace) {
            throw new DependencyException(sprintf('Could not find replaced package %s.', $name));
        }

        return $replace;
    }

    public function hasReplaceByName(string $name): bool
    {
        return !$this->filterByName($name)->isEmpty();
    }

    protected function allowedInstance(): string
    {
        return ReplaceInterface::class;
    }

    private function filterByName(string $name): self
    {
        return $this->filter(static function (ReplaceInterface $replace) use ($name): bool {
            return $replace->getPackageName() === $name;
        });
    }
}
