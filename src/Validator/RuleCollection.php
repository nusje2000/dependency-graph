<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Validator;

use Aeviiq\Collection\ObjectCollection;
use ArrayIterator;

/**
 * @phpstan-extends ImmutableObjectCollection<int|string, PackageInterface>
 * @psalm-extends   ImmutableObjectCollection<int|string, PackageInterface>
 *
 * @method ArrayIterator|RuleInterface[] getIterator()
 * @method RuleInterface|null first()
 * @method RuleInterface|null last()
 */
final class RuleCollection extends ObjectCollection
{
    protected function allowedInstance(): string
    {
        return RuleInterface::class;
    }
}