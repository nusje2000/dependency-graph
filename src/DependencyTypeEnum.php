<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

use MyCLabs\Enum\Enum;
use Nusje2000\DependencyGraph\Exception\DependencyException;

final class DependencyTypeEnum extends Enum
{
    public const PACKAGE = 'package';
    public const EXTENSION = 'extension';
    public const PHP = 'php';
    private const PACKAGE_NAME_REGEX = '/[a-z0-9-_]+\/[a-z0-9-_]+/i';
    private const EXTENSION_NAME_REGEX = '/ext-[a-z0-9-_]+/i';
    private const PHP_DEPENDENCY_NAME = 'php';

    public static function createFromDependencyName(string $name): self
    {
        if (1 === preg_match(self::PACKAGE_NAME_REGEX, $name)) {
            return new static(self::PACKAGE);
        }

        if (1 === preg_match(self::EXTENSION_NAME_REGEX, $name)) {
            return new static(self::EXTENSION);
        }

        if (false !== stripos($name, self::PHP_DEPENDENCY_NAME)) {
            return new static(self::PHP);
        }

        throw DependencyException::unresolvableDependencyType($name);
    }
}
