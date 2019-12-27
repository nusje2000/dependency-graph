<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests;

use Nusje2000\DependencyGraph\Cache\FileCache;
use Nusje2000\DependencyGraph\DependencyGraph;
use PHPUnit\Framework\TestCase;

final class DependencyGraphTest extends TestCase
{
    public function testBuild(): void
    {
        $rootPath = realpath(__DIR__ . '/../example-structure');

        $cache = new FileCache();
        self::assertFalse($cache->exists($rootPath));

        $graph = DependencyGraph::build($rootPath, null, $cache);
        $packages = $graph->getPackages();

        self::assertCount(7, $packages);
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-1'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-2'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-3'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-4'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-example-project'));
        self::assertTrue($packages->hasPackageByName('bar/bar-package'));
        self::assertTrue($packages->hasPackageByName('foo/foo-package'));
        self::assertTrue($cache->exists($graph->getRootPath()));

        $cache->remove($graph->getRootPath());
    }
}
