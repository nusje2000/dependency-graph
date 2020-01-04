<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Validator;

use Aeviiq\Collection\ObjectCollection;
use Aeviiq\Collection\StringCollection;
use ArrayIterator;

/**
 * @phpstan-extends ImmutableObjectCollection<int|string, PackageInterface>
 * @psalm-extends   ImmutableObjectCollection<int|string, PackageInterface>
 *
 * @method ArrayIterator|ViolationInterface[] getIterator()
 * @method ViolationInterface|null first()
 * @method ViolationInterface|null last()
 */
final class ViolationCollection extends ObjectCollection
{
    public function getMessages(): StringCollection
    {
        return new StringCollection($this->map(static function (ViolationInterface $violation) {
            return $violation->getMessage();
        }));
    }

    protected function allowedInstance(): string
    {
        return ViolationInterface::class;
    }
}
