<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests;

use Nusje2000\DependencyGraph\Package;
use Nusje2000\DependencyGraph\PackageCollection;
use PHPUnit\Framework\TestCase;

class DepencencyCollectionTest extends TestCase
{
    public function testGetDepencenciesRecursive(): void
    {
        $package5 = new Package('foo/package-5');
        $package6 = new Package('foo/package-6');

        $package2 = new Package('foo/package-2');
        $package3 = new Package('foo/package-3');
        $package4 = new Package('foo/package-4', null, new PackageCollection([
            $package5,
            $package6,
        ]));

        $package1 = new Package('foo/package-1', null, new PackageCollection([
            $package2,
            $package3,
            $package4,
        ]));

        $package7 = new Package('foo/package-7');
        $package8 = new Package('foo/package-8');

        $dependencies = new PackageCollection([
            $package1,
            $package2,
            $package3,
            $package7,
            $package8,
        ]);

        $resolvedDependencies = $dependencies->getDepencenciesRecursive();

        self::assertNotSame($resolvedDependencies, $dependencies);
        self::assertCount(8, $resolvedDependencies);

        self::assertTrue($resolvedDependencies->contains($package1));
        self::assertTrue($resolvedDependencies->contains($package2));
        self::assertTrue($resolvedDependencies->contains($package3));
        self::assertTrue($resolvedDependencies->contains($package4));
        self::assertTrue($resolvedDependencies->contains($package5));
        self::assertTrue($resolvedDependencies->contains($package6));
        self::assertTrue($resolvedDependencies->contains($package7));
        self::assertTrue($resolvedDependencies->contains($package8));
    }
}
