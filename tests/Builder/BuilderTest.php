<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests\Builder;

use Nusje2000\DependencyGraph\Builder\Builder;
use PHPUnit\Framework\TestCase;

final class BuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $builder = new Builder();
        $graph = $builder->build(realpath(__DIR__ . '/../../example-structure'));
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
        self::assertCount(1, $package1->getDirectDependencies());
        self::assertCount(2, $package1->getDependencies());
        self::assertTrue($package1->hasDirectDependency('foo/foo-package'));
        self::assertFalse($package1->hasDirectDependency('bar/bar-package'));
        self::assertTrue($package1->hasDependency('foo/foo-package'));
        self::assertTrue($package1->hasDependency('bar/bar-package'));

        self::assertSame(['Nusje2000\\Example\\Package1\\'], $package1->getRegisteredNamespaces()->toArray());

        $package2 = $packages->getPackageByName('nusje2000/dependency-graph-internal-2');
        self::assertCount(1, $package2->getDirectDependencies());
        self::assertCount(1, $package2->getDependencies());
        self::assertTrue($package2->hasDirectDependency('bar/bar-package'));
        self::assertTrue($package2->hasDependency('bar/bar-package'));

        self::assertSame(['Nusje2000\\Example\\Package2\\'], $package2->getRegisteredNamespaces()->toArray());

        $package3 = $packages->getPackageByName('nusje2000/dependency-graph-internal-3');
        self::assertCount(0, $package3->getDirectDependencies());
        self::assertCount(0, $package3->getDependencies());

        self::assertSame(['Nusje2000\\Example\\Package3\\'], $package3->getRegisteredNamespaces()->toArray());

        $package4 = $packages->getPackageByName('nusje2000/dependency-graph-internal-4');
        self::assertCount(2, $package4->getDirectDependencies());
        self::assertCount(2, $package4->getDependencies());
        self::assertTrue($package4->hasDirectDependency('foo/foo-package'));
        self::assertTrue($package4->hasDirectDependency('bar/bar-package'));
        self::assertTrue($package4->hasDependency('foo/foo-package'));
        self::assertTrue($package4->hasDependency('bar/bar-package'));

        self::assertSame(['Nusje2000\\Example\\Package4\\'], $package4->getRegisteredNamespaces()->toArray());

        $projectPackage = $packages->getPackageByName('nusje2000/dependency-graph-example-project');
        self::assertCount(2, $projectPackage->getDirectDependencies());
        self::assertCount(2, $projectPackage->getDependencies());
        self::assertTrue($projectPackage->hasDirectDependency('foo/foo-package'));
        self::assertTrue($projectPackage->hasDirectDependency('bar/bar-package'));
        self::assertTrue($projectPackage->hasDependency('foo/foo-package'));
        self::assertTrue($projectPackage->hasDependency('bar/bar-package'));

        self::assertSame(['Nusje2000\\Example\\'], $projectPackage->getRegisteredNamespaces()->toArray());

        $fooPackage = $packages->getPackageByName('foo/foo-package');
        self::assertCount(1, $fooPackage->getDirectDependencies());
        self::assertCount(1, $fooPackage->getDependencies());
        self::assertTrue($fooPackage->hasDirectDependency('bar/bar-package'));
        self::assertTrue($fooPackage->hasDependency('bar/bar-package'));

        self::assertSame(['Foo\\Package\\'], $fooPackage->getRegisteredNamespaces()->toArray());

        $barPackage = $packages->getPackageByName('bar/bar-package');
        self::assertCount(0, $barPackage->getDirectDependencies());
        self::assertCount(0, $barPackage->getDependencies());

        self::assertSame(['Bar\\Package\\'], $barPackage->getRegisteredNamespaces()->toArray());
    }
}
