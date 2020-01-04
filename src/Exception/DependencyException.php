<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Exception;

use LogicException;

final class DependencyException extends LogicException implements ExceptionInterface
{
    public static function unresolvableDependencyType(string $dependency): self
    {
        return new static(sprintf('Could not resolve type for dependency named "%s".', $dependency));
    }
}
