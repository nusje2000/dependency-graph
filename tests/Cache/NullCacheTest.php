<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests\Cache;

use Nusje2000\DependencyGraph\Cache\NullCache;
use Nusje2000\DependencyGraph\DependencyGraph;
use Nusje2000\DependencyGraph\Exception\CacheException;
use Nusje2000\DependencyGraph\PackageCollection;
use PHPUnit\Framework\TestCase;

final class NullCacheTest extends TestCase
{
    public function testSave(): void
    {
        $cache = new NullCache();
        $cache->save(new DependencyGraph($this->getRootPath(), new PackageCollection()));
        $this->addToAssertionCount(1);
    }

    /**
     * @depends testSave
     */
    public function testExists(): void
    {
        $cache = new NullCache();
        self::assertFalse($cache->exists($this->getRootPath()));
    }

    /**
     * @depends testExists
     */
    public function testLoad(): void
    {
        $cache = new NullCache();
        $this->expectException(CacheException::class);
        $cache->load($this->getRootPath());
    }

    /**
     * @depends testLoad
     */
    public function testRemove(): void
    {
        $cache = new NullCache();
        $cache->remove($this->getRootPath());
        $this->addToAssertionCount(1);
    }

    private function getRootPath(): string
    {
        return realpath(__DIR__ . '/../../example-structure');
    }
}
