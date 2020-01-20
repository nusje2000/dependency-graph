<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Exception;

use Exception;

final class AuthorException extends Exception implements ExceptionInterface
{
    public static function notFoundByName(string $name): self
    {
        return new static(sprintf('Could not find an author with name "%s".', $name));
    }

    public static function notFoundByEmail(string $email): self
    {
        return new static(sprintf('Could not find an author with email "%s".', $email));
    }
}
