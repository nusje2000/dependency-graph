<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Author;

use Aeviiq\Collection\ObjectCollection;
use ArrayIterator;
use Closure;

/**
 * @phpstan-extends ImmutableObjectCollection<int|string, AuthorInterface>
 * @psalm-extends   ImmutableObjectCollection<int|string, AuthorInterface>
 *
 * @method ArrayIterator|AuthorInterface[] getIterator()
 * @method AuthorInterface|null first()
 * @method AuthorInterface|null last()
 * @method AuthorCollection filter(Closure $closure)
 */
final class AuthorCollection extends ObjectCollection
{
    protected function allowedInstance(): string
    {
        return AuthorInterface::class;
    }
}
