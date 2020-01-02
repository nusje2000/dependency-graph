<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests;

use Nusje2000\DependencyGraph\DependencyTypeEnum;
use PHPUnit\Framework\TestCase;

final class DependencyTypeEnumTest extends TestCase
{
    /**
     * @dataProvider dependencyNameProvider
     */
    public function testCreateFromDependencyName(string $name, DependencyTypeEnum $expected): void
    {
        self::assertTrue(DependencyTypeEnum::createFromDependencyName($name)->equals($expected));
    }

    /**
     * @return array<string, mixed>
     */
    public function dependencyNameProvider(): array
    {
        return [
            'package' => [
                'ven-dor1/pack-age_01',
                new DependencyTypeEnum(DependencyTypeEnum::PACKAGE),
            ],
            'composer_plugin' => [
                'composer-plugin-some_thing',
                new DependencyTypeEnum(DependencyTypeEnum::COMPOSER_PLUGIN),
            ],
            'php' => [
                'php',
                new DependencyTypeEnum(DependencyTypeEnum::PHP),
            ],
            'php_lib' => [
                'lib-bar',
                new DependencyTypeEnum(DependencyTypeEnum::PHP_LIB),
            ],
            'php_extension' => [
                'ext-foo',
                new DependencyTypeEnum(DependencyTypeEnum::PHP_EXTENSION),
            ],
            'unknown' => [
                'just_random_chars',
                new DependencyTypeEnum(DependencyTypeEnum::UNKNOWN),
            ],
        ];
    }
}
