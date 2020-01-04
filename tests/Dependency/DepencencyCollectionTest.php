<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests\Dependency;

use Nusje2000\DependencyGraph\Exception\PackageException;
use Nusje2000\DependencyGraph\Package\Package;
use Nusje2000\DependencyGraph\Package\PackageCollection;
use PHPUnit\Framework\TestCase;

final class DepencencyCollectionTest extends TestCase
{
    public function testHasPackageByName(): void
    {
        $collection = new PackageCollection([
            new Package('some/package', '/path/to/package', false),
        ]);

        self::assertTrue($collection->hasPackageByName('some/package'));
        self::assertFalse($collection->hasPackageByName('some/other-package'));
    }

    public function testGetPackageByName(): void
    {
        $registeredPackage = new Package('some/package', '/path/to/package', false);
        $collection = new PackageCollection([$registeredPackage]);

        self::assertSame($registeredPackage, $collection->getPackageByName('some/package'));

        $this->expectException(PackageException::class);
        $collection->getPackageByName('some/other-package');
    }
}
