<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Exception;

use LogicException;

final class PackageException extends LogicException implements ExceptionInterface
{
    public static function notFound(string $name): self
    {
        return new static(sprintf('No package found with name "%s".', $name));
    }
}
