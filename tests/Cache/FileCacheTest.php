<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests\Cache;

use Aeviiq\Collection\StringCollection;
use Nusje2000\DependencyGraph\Cache\FileCache;
use Nusje2000\DependencyGraph\DependencyGraph;
use Nusje2000\DependencyGraph\Package;
use Nusje2000\DependencyGraph\PackageCollection;
use PHPUnit\Framework\TestCase;

final class FileCacheTest extends TestCase
{
    public function testSave(): FileCache
    {
        $barPackage = new Package('bar/bar-package', new StringCollection(['SomeOtherNamespace']));
        $graph = new DependencyGraph($this->getRootPath(), new PackageCollection([
            new Package('foo/foo-package', new StringCollection(['SomeNamespace']), new PackageCollection([
                $barPackage,
            ])),
            $barPackage,
        ]));

        $cache = new FileCache();

        self::assertFalse($cache->exists($this->getRootPath()));
        $cache->save($graph);
        self::assertTrue($cache->exists($this->getRootPath()));

        return $cache;
    }

    /**
     * @depends testSave
     */
    public function testExists(FileCache $cache): FileCache
    {
        self::assertTrue($cache->exists($this->getRootPath()));

        return $cache;
    }

    /**
     * @depends testExists
     */
    public function testLoad(FileCache $cache): FileCache
    {
        $loadedGraph = $cache->load($this->getRootPath());

        $packages = $loadedGraph->getPackages();
        self::assertTrue($packages->hasPackageByName('bar/bar-package'));
        self::assertTrue($packages->hasPackageByName('foo/foo-package'));

        self::assertSame(
            $packages->getPackageByName('bar/bar-package'),
            $packages->getPackageByName('foo/foo-package')->getDirectDependencies()->getPackageByName('bar/bar-package')
        );

        return $cache;
    }

    /**
     * @depends testLoad
     */
    public function testRemove(FileCache $cache): void
    {
        self::assertTrue($cache->exists($this->getRootPath()));
        $cache->remove($this->getRootPath());
        self::assertFalse($cache->exists($this->getRootPath()));
    }

    private function getRootPath(): string
    {
        return realpath(__DIR__ . '/../../example-structure');
    }
}
