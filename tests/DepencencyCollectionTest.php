<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests;

use Nusje2000\DependencyGraph\Exception\PackageException;
use Nusje2000\DependencyGraph\Package;
use Nusje2000\DependencyGraph\PackageCollection;
use PHPUnit\Framework\TestCase;

final class DepencencyCollectionTest extends TestCase
{
    public function testHasPackageByName(): void
    {
        $collection = new PackageCollection([
            new Package('some/package', '/path/to/package'),
        ]);

        self::assertTrue($collection->hasPackageByName('some/package'));
        self::assertFalse($collection->hasPackageByName('some/other-package'));
    }

    public function testGetPackageByName(): void
    {
        $registeredPackage = new Package('some/package', '/path/to/package');
        $collection = new PackageCollection([$registeredPackage]);

        self::assertSame($registeredPackage, $collection->getPackageByName('some/package'));

        $this->expectException(PackageException::class);
        $collection->getPackageByName('some/other-package');
    }
}
