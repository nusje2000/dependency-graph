<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Exception;

use Exception;

final class ReplaceException extends Exception implements ExceptionInterface
{
    public static function notFound(string $name): self
    {
        return new static(sprintf('Could not find replace with name "%s".', $name));
    }
}
