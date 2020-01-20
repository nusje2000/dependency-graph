<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests\Package;

use Nusje2000\DependencyGraph\Dependency\Dependency;
use Nusje2000\DependencyGraph\Dependency\DependencyCollection;
use Nusje2000\DependencyGraph\Dependency\DependencyTypeEnum;
use Nusje2000\DependencyGraph\Exception\PackageException;
use Nusje2000\DependencyGraph\Package\Package;
use Nusje2000\DependencyGraph\Package\PackageCollection;
use PHPUnit\Framework\TestCase;

final class PackageCollectionTest extends TestCase
{
    public function testGetPackageByName(): void
    {
        $package = $this->getCollection()->getPackageByName('some/package-1');
        self::assertSame('some/package-1', $package->getName());

        $this->expectException(PackageException::class);
        $this->getCollection()->getPackageByName('some/package-3');
    }

    public function testHasPackageByName(): void
    {
        self::assertTrue($this->getCollection()->hasPackageByName('some/package-1'));
        self::assertFalse($this->getCollection()->hasPackageByName('some/package-3'));
    }

    public function testFilterByDependency(): void
    {
        $packages = $this->getCollection()->filterByDependency('some/other-package');
        self::assertCount(1, $packages);
        self::assertTrue($packages->hasPackageByName('some/package-1'));
    }

    private function getCollection(): PackageCollection
    {
        return new PackageCollection([
            new Package('some/package-1', '/some/package1', false, new DependencyCollection([
                new Dependency('some/other-package', '1.0', false, new DependencyTypeEnum(DependencyTypeEnum::PACKAGE)),
            ])),
            new Package('some/package-2', '/some/package2', false, new DependencyCollection([
                new Dependency('some/second-other-package', '1.0', false, new DependencyTypeEnum(DependencyTypeEnum::PACKAGE)),
            ])),
        ]);
    }
}
