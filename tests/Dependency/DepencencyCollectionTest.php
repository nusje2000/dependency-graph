<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests\Dependency;

use Nusje2000\DependencyGraph\Dependency\Dependency;
use Nusje2000\DependencyGraph\Dependency\DependencyCollection;
use Nusje2000\DependencyGraph\Dependency\DependencyTypeEnum;
use Nusje2000\DependencyGraph\Exception\DependencyException;
use PHPUnit\Framework\TestCase;

final class DepencencyCollectionTest extends TestCase
{
    public function testFilterExtensions(): void
    {
        $dependencies = $this->getCollection()->filterExtensions();
        self::assertCount(1, $dependencies);
        self::assertTrue($dependencies->hasDependencyByName('ext-some-extension'));
    }

    public function testFilterPackage(): void
    {
        $dependencies = $this->getCollection()->filterPackages();
        self::assertCount(2, $dependencies);
        self::assertTrue($dependencies->hasDependencyByName('some/package'));
        self::assertTrue($dependencies->hasDependencyByName('some/dev-package'));
    }

    public function testFilterByType(): void
    {
        $dependencies = $this->getCollection()->filterByType(new DependencyTypeEnum(DependencyTypeEnum::PHP_EXTENSION));
        self::assertCount(1, $dependencies);
        self::assertTrue($dependencies->hasDependencyByName('ext-some-extension'));
    }

    public function testGetDependencyByName(): void
    {
        $dependency = $this->getCollection()->getDependencyByName('some/package');
        self::assertSame($dependency->getName(), 'some/package');
        self::assertSame($dependency->getVersionConstraint(), '^1.0');
        self::assertTrue($dependency->getType()->equals(new DependencyTypeEnum(DependencyTypeEnum::PACKAGE)));
        self::assertFalse($dependency->isDev());

        $this->expectException(DependencyException::class);
        $this->getCollection()->getDependencyByName('non-existant');
    }

    public function testHasDependencyByName(): void
    {
        self::assertTrue($this->getCollection()->hasDependencyByName('some/package'));
        self::assertFalse($this->getCollection()->hasDependencyByName('non-existant'));
    }

    private function getCollection(): DependencyCollection
    {
        return new DependencyCollection([
            new Dependency('php', '^7.2', false, new DependencyTypeEnum(DependencyTypeEnum::PHP)),
            new Dependency('ext-some-extension', '^1.0', false, new DependencyTypeEnum(DependencyTypeEnum::PHP_EXTENSION)),
            new Dependency('some/package', '^1.0', false, new DependencyTypeEnum(DependencyTypeEnum::PACKAGE)),
            new Dependency('some/dev-package', '^1.0', true, new DependencyTypeEnum(DependencyTypeEnum::PACKAGE)),
        ]);
    }
}
