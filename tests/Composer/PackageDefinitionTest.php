<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests\Composer;

use Nusje2000\DependencyGraph\Composer\PackageDefinition;
use Nusje2000\DependencyGraph\Exception\DefinitionException;
use PHPStan\Testing\TestCase;
use Symfony\Component\Finder\SplFileInfo;

final class PackageDefinitionTest extends TestCase
{
    public function testGetName(): void
    {
        self::assertSame('nusje2000/dependency-graph-example-project', $this->getPackageDefinition()->getName());
        self::assertSame('nusje2000/dependency-graph-internal-5', $this->getMinimalPackageDefinition()->getName());

        $this->expectException(DefinitionException::class);
        $this->getInvalidPackageDefinition()->getName();
    }

    public function testGetPackageDirectory(): void
    {
        self::assertSame(
            realpath(__DIR__ . '/../../example-structure'),
            $this->getPackageDefinition()->getPackageDirectory()
        );

        self::assertSame(
            realpath(__DIR__ . '/../../example-structure/src/Package5'),
            $this->getMinimalPackageDefinition()->getPackageDirectory()
        );
    }

    public function testGetAuthors(): void
    {
        self::assertSame([
            [
                'name' => 'Maarten Nusteling',
                'email' => 'maarten.nusteling@gmail.com',
                'role' => 'maintainer',
                'homepage' => 'https://github.com/nusje2000',
            ],
        ], $this->getPackageDefinition()->getAuthors());

        self::assertSame([], $this->getMinimalPackageDefinition()->getAuthors());
    }

    public function testGetDependencies(): void
    {
        self::assertSame([
            'foo/foo-package' => '2.0.0',
            'bar/bar-package' => '1.0.0',
        ], $this->getPackageDefinition()->getDependencies());

        self::assertSame([], $this->getMinimalPackageDefinition()->getDependencies());
    }

    public function testGetDevDependencies(): void
    {
        self::assertSame([
            'phpunit/phpunit' => '^8.5',
        ], $this->getPackageDefinition()->getDevDependencies());

        self::assertSame([], $this->getMinimalPackageDefinition()->getDevDependencies());
    }

    public function testGetReplaces(): void
    {
        self::assertSame([
            'nusje2000/dependency-graph-internal-1' => 'self.version',
            'nusje2000/dependency-graph-internal-2' => 'self.version',
            'nusje2000/dependency-graph-internal-3' => 'self.version',
            'nusje2000/dependency-graph-internal-4' => 'self.version',
            'nusje2000/dependency-graph-internal-5' => 'self.version',
        ], $this->getPackageDefinition()->getReplaces());

        self::assertSame([], $this->getMinimalPackageDefinition()->getReplaces());
    }

    public function getPackageDefinition(): PackageDefinition
    {
        return PackageDefinition::createFromFile(
            new SplFileInfo(realpath(__DIR__ . '/../../example-structure/composer.json'), '', 'composer.json')
        );
    }

    public function getMinimalPackageDefinition(): PackageDefinition
    {
        return PackageDefinition::createFromFile(
            new SplFileInfo(realpath(__DIR__ . '/../../example-structure/src/Package5/composer.json'), '', 'composer.json')
        );
    }

    public function getInvalidPackageDefinition(): PackageDefinition
    {
        return PackageDefinition::createFromFile(
            new SplFileInfo(realpath(__DIR__ . '/../../example-structure/vendor/invalid/package/composer.json'), '', 'composer.json')
        );
    }
}
