<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests;

use Nusje2000\DependencyGraph\Cache\FileCache;
use Nusje2000\DependencyGraph\DependencyGraph;
use Nusje2000\DependencyGraph\Exception\PackageException;
use Nusje2000\DependencyGraph\Package\PackageCollection;
use PHPUnit\Framework\TestCase;
use Throwable;

final class DependencyGraphTest extends TestCase
{
    /**
     * @var string
     */
    private $rootPath;

    /**
     * @var FileCache
     */
    private $cache;

    protected function setUp(): void
    {
        $this->rootPath = (string)realpath(__DIR__ . '/../example-structure');
        $this->cache = new FileCache();
    }

    public function testBuild(): DependencyGraph
    {
        $graph = DependencyGraph::build($this->rootPath);
        $packages = $graph->getPackages();

        self::assertCount(8, $packages);
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-1'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-2'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-3'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-4'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-example-project'));
        self::assertTrue($packages->hasPackageByName('bar/bar-package'));
        self::assertTrue($packages->hasPackageByName('foo/foo-package'));

        return $graph;
    }

    public function testBuildWithCache(): void
    {
        self::assertFalse($this->cache->exists($this->rootPath));

        $graph = DependencyGraph::build($this->rootPath, null, $this->cache);
        $packages = $graph->getPackages();

        self::assertCount(8, $packages);
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-1'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-2'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-3'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-4'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-example-project'));
        self::assertTrue($packages->hasPackageByName('bar/bar-package'));
        self::assertTrue($packages->hasPackageByName('foo/foo-package'));
    }

    /**
     * @depends testBuildWithCache
     */
    public function testBuildFromCache(): void
    {
        self::assertTrue($this->cache->exists($this->rootPath));

        $graph = DependencyGraph::build($this->rootPath, null, $this->cache);
        $packages = $graph->getPackages();

        self::assertCount(8, $packages);
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-1'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-2'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-3'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-4'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-5'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-example-project'));
        self::assertTrue($packages->hasPackageByName('bar/bar-package'));
        self::assertTrue($packages->hasPackageByName('foo/foo-package'));

        $this->cache->remove($this->rootPath);
    }

    /**
     * @depends testBuild
     */
    public function testGetRootPackage(DependencyGraph $graph): void
    {
        self::assertSame('nusje2000/dependency-graph-example-project', $graph->getRootPackage()->getName());
    }

    public function testGetRootPackageException(): void
    {
        $graph = new DependencyGraph('/', new PackageCollection());
        $this->expectException(PackageException::class);
        $graph->getRootPackage();
    }

    /**
     * @depends testBuild
     */
    public function testGetSubPackages(DependencyGraph $graph): void
    {
        $packages = $graph->getSubPackages();

        self::assertCount(5, $packages);
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-1'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-2'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-3'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-4'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-5'));
    }

    /**
     * @depends testBuild
     */
    public function testGetPackage(DependencyGraph $graph): void
    {
        $package = $graph->getPackage('nusje2000/dependency-graph-internal-1');
        self::assertSame('nusje2000/dependency-graph-internal-1', $package->getName());
    }

    /**
     * @depends testBuild
     */
    public function testHasPackage(DependencyGraph $graph): void
    {
        self::assertTrue($graph->hasPackage('nusje2000/dependency-graph-internal-1'));
    }

    protected function onNotSuccessfulTest(Throwable $throwable): void
    {
        $this->cache->remove($this->rootPath);

        throw $throwable;
    }
}
