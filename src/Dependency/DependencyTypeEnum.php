<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Dependency;

use MyCLabs\Enum\Enum;

/**
 * @extends Enum<string>
 */
final class DependencyTypeEnum extends Enum
{
    public const PACKAGE = 'package';
    public const COMPOSER_PLUGIN = 'compposer-plugin';
    public const PHP = 'php';
    public const PHP_LIB = 'php_lib';
    public const PHP_EXTENSION = 'extension';
    public const UNKNOWN = 'unknown';
    private const PACKAGE_NAME_REGEX = '/[a-z0-9-_]+\/[a-z0-9-_]+/i';
    private const COMPOSER_PLUGIN_NAME_REGEX = '/composer-plugin-[a-z0-9-_]+/i';
    private const PHP_DEPENDENCY_NAME = 'php';
    private const PHP_EXTENSION_NAME_REGEX = '/ext-[a-z0-9-_]+/i';
    private const PHP_LIB_NAME_REGEX = '/lib-[a-z0-9-_]+/i';

    public static function createFromDependencyName(string $name): self
    {
        if (1 === preg_match(self::PACKAGE_NAME_REGEX, $name)) {
            return new static(self::PACKAGE);
        }

        if (1 === preg_match(self::COMPOSER_PLUGIN_NAME_REGEX, $name)) {
            return new static(self::COMPOSER_PLUGIN);
        }

        if (1 === preg_match(self::PHP_EXTENSION_NAME_REGEX, $name)) {
            return new static(self::PHP_EXTENSION);
        }

        if (1 === preg_match(self::PHP_LIB_NAME_REGEX, $name)) {
            return new static(self::PHP_LIB);
        }

        if (false !== stripos($name, self::PHP_DEPENDENCY_NAME)) {
            return new static(self::PHP);
        }

        return new static(self::UNKNOWN);
    }
}
