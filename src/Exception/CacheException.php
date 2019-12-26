<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Exception;

use LogicException;

final class CacheException extends LogicException implements ExceptionInterface
{
    public static function invalidCache(string $cacheLocation): self
    {
        return new static(sprintf('Cache located at "%s" is invalid.', $cacheLocation));
    }

    public static function notFound(string $cacheLocation): self
    {
        return new static(sprintf('No cache found (searched for "%s")', $cacheLocation));
    }
}
