<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests\Replace;

use Nusje2000\DependencyGraph\Exception\ReplaceException;
use Nusje2000\DependencyGraph\Replace\Replace;
use Nusje2000\DependencyGraph\Replace\ReplaceCollection;
use PHPStan\Testing\TestCase;

final class ReplaceCollectionTest extends TestCase
{
    public function testGetReplaceByName(): void
    {
        $collection = $this->getCollection();

        $foo = $collection->getReplaceByName('foo/foo-package');
        self::assertSame('foo/foo-package', $foo->getPackageName());
        self::assertSame('1.0', $foo->getVersion());

        $bar = $collection->getReplaceByName('bar/bar-package');
        self::assertSame('bar/bar-package', $bar->getPackageName());
        self::assertSame('2.0', $bar->getVersion());

        $this->expectException(ReplaceException::class);
        $collection->getReplaceByName('invalid');
    }

    public function testHasReplaceByName(): void
    {
        $collection = $this->getCollection();
        self::assertTrue($collection->hasReplaceByName('foo/foo-package'));
        self::assertTrue($collection->hasReplaceByName('bar/bar-package'));
        self::assertFalse($collection->hasReplaceByName('baz/baz-package'));
    }

    private function getCollection(): ReplaceCollection
    {
        return new ReplaceCollection([
            new Replace('foo/foo-package', '1.0'),
            new Replace('bar/bar-package', '2.0'),
        ]);
    }
}
