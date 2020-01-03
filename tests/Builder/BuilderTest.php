<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests\Builder;

use Nusje2000\DependencyGraph\Builder\Builder;
use PHPUnit\Framework\TestCase;

final class BuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $exampleProjectDir = realpath(__DIR__ . '/../../example-structure');

        $builder = new Builder();
        $graph = $builder->build($exampleProjectDir);
        $packages = $graph->getPackages();

        self::assertCount(7, $packages);

        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-1'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-2'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-3'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-4'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-example-project'));
        self::assertTrue($packages->hasPackageByName('bar/bar-package'));
        self::assertTrue($packages->hasPackageByName('foo/foo-package'));

        $package1 = $packages->getPackageByName('nusje2000/dependency-graph-internal-1');
        self::assertCount(1, $package1->getDependencies());
        self::assertFalse($package1->isFromVendor());
        self::assertTrue($package1->hasDependency('foo/foo-package'));
        self::assertFalse($package1->hasDependency('bar/bar-package'));
        self::assertSame(realpath($exampleProjectDir . '/src/Package1'), $package1->getPackageLocation());

        $package2 = $packages->getPackageByName('nusje2000/dependency-graph-internal-2');
        self::assertCount(1, $package2->getDependencies());
        self::assertFalse($package2->isFromVendor());
        self::assertTrue($package2->hasDependency('bar/bar-package'));
        self::assertSame(realpath($exampleProjectDir . '/src/Package2'), $package2->getPackageLocation());

        $package3 = $packages->getPackageByName('nusje2000/dependency-graph-internal-3');
        self::assertCount(0, $package3->getDependencies());
        self::assertFalse($package3->isFromVendor());
        self::assertSame(realpath($exampleProjectDir . '/src/Package3'), $package3->getPackageLocation());

        $package4 = $packages->getPackageByName('nusje2000/dependency-graph-internal-4');
        self::assertCount(2, $package4->getDependencies());
        self::assertFalse($package4->isFromVendor());
        self::assertTrue($package4->hasDependency('foo/foo-package'));
        self::assertTrue($package4->hasDependency('bar/bar-package'));
        self::assertTrue($package4->getDependencies()->getDependencyByName('bar/bar-package')->isDev());
        self::assertFalse($package4->getDependencies()->getDependencyByName('foo/foo-package')->isDev());
        self::assertSame(realpath($exampleProjectDir . '/src/Package4'), $package4->getPackageLocation());

        $projectPackage = $packages->getPackageByName('nusje2000/dependency-graph-example-project');
        self::assertCount(2, $projectPackage->getDependencies());
        self::assertFalse($projectPackage->isFromVendor());
        self::assertTrue($projectPackage->hasDependency('foo/foo-package'));
        self::assertTrue($projectPackage->hasDependency('bar/bar-package'));
        self::assertSame($exampleProjectDir, $projectPackage->getPackageLocation());

        $fooPackage = $packages->getPackageByName('foo/foo-package');
        self::assertCount(1, $fooPackage->getDependencies());
        self::assertTrue($fooPackage->hasDependency('bar/bar-package'));
        self::assertTrue($fooPackage->isFromVendor());
        self::assertSame(realpath($exampleProjectDir . '/vendor/foo/package'), $fooPackage->getPackageLocation());

        $barPackage = $packages->getPackageByName('bar/bar-package');
        self::assertCount(0, $barPackage->getDependencies());
        self::assertTrue($barPackage->isFromVendor());
        self::assertSame(realpath($exampleProjectDir . '/vendor/bar/package'), $barPackage->getPackageLocation());
    }
}
