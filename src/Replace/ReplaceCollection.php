<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Replace;

use Aeviiq\Collection\ImmutableObjectCollection;
use ArrayIterator;
use Closure;
use Nusje2000\DependencyGraph\Exception\ReplaceException;

/**
 * @phpstan-extends ImmutableObjectCollection<int|string, ReplaceInterface>
 * @psalm-extends   ImmutableObjectCollection<int|string, ReplaceInterface>
 *
 * @method ArrayIterator|ReplaceInterface[] getIterator()
 * @method ReplaceInterface|null first()
 * @method ReplaceInterface|null last()
 * @method ReplaceCollection filter(Closure $closure)
 */
final class ReplaceCollection extends ImmutableObjectCollection
{
    public function getReplaceByName(string $name): ReplaceInterface
    {
        $replace = $this->filterByName($name)->first();

        if (null === $replace) {
            throw ReplaceException::notFound($name);
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
