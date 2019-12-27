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
    public function testSave(): void
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
    }

    /**
     * @depends testSave
     */
    public function testExists(): void
    {
        $cache = new FileCache();
        self::assertTrue($cache->exists($this->getRootPath()));
    }

    /**
     * @depends testExists
     */
    public function testLoad(): void
    {
        $cache = new FileCache();
        $loadedGraph = $cache->load($this->getRootPath());

        $packages = $loadedGraph->getPackages();
        self::assertTrue($packages->hasPackageByName('bar/bar-package'));
        self::assertTrue($packages->hasPackageByName('foo/foo-package'));

        self::assertSame(
            $packages->getPackageByName('bar/bar-package'),
            $packages->getPackageByName('foo/foo-package')->getDirectDependencies()->getPackageByName('bar/bar-package')
        );
    }

    /**
     * @depends testLoad
     */
    public function testRemove(): void
    {
        $cache = new FileCache();
        self::assertTrue($cache->exists($this->getRootPath()));
        $cache->remove($this->getRootPath());
        self::assertFalse($cache->exists($this->getRootPath()));
    }

    private function getRootPath(): string
    {
        return realpath(__DIR__ . '/../../example-structure');
    }
}
