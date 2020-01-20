<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Exception;

use LogicException;

final class DependencyException extends LogicException implements ExceptionInterface
{
    public static function notFound(string $name): self
    {
        return new static(sprintf('Could not find dependency %s.', $name));
    }
}
