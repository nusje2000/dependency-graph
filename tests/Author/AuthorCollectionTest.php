<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests\Author;

use Nusje2000\DependencyGraph\Author\Author;
use Nusje2000\DependencyGraph\Author\AuthorCollection;
use Nusje2000\DependencyGraph\Exception\AuthorException;
use PHPStan\Testing\TestCase;

final class AuthorCollectionTest extends TestCase
{
    public function testGetAuthorByName(): void
    {
        $collection = $this->getCollection();

        $foo = $collection->getAuthorByName('Foo Foo');
        self::assertSame('Foo Foo', $foo->getName());

        $bar = $collection->getAuthorByName('Bar Bar');
        self::assertSame('Bar Bar', $bar->getName());

        $this->expectException(AuthorException::class);
        $collection->getAuthorByName('Baz Baz');
    }

    public function testHasAuthorByName(): void
    {
        $collection = $this->getCollection();
        self::assertTrue($collection->hasAuthorByName('Foo Foo'));
        self::assertTrue($collection->hasAuthorByName('Bar Bar'));
        self::assertFalse($collection->hasAuthorByName('Baz Baz'));
    }

    public function testGetAuthorByEmail(): void
    {
        $collection = $this->getCollection();

        $foo = $collection->getAuthorByEmail('foo@foo.com');
        self::assertSame('foo@foo.com', $foo->getEmail());

        $bar = $collection->getAuthorByEmail('bar@bar.com');
        self::assertSame('bar@bar.com', $bar->getEmail());

        $this->expectException(AuthorException::class);
        $collection->getAuthorByEmail('baz@baz.com');
    }

    public function testHasAuthorByEmail(): void
    {
        $collection = $this->getCollection();
        self::assertTrue($collection->hasAuthorByEmail('foo@foo.com'));
        self::assertTrue($collection->hasAuthorByEmail('bar@bar.com'));
        self::assertFalse($collection->hasAuthorByEmail('baz@baz.com'));
    }

    private function getCollection(): AuthorCollection
    {
        return new AuthorCollection([
            new Author('Foo Foo', 'foo@foo.com', 'foo.io', 'fooing'),
            new Author('Bar Bar', 'bar@bar.com', 'bar.io', 'baring'),
        ]);
    }
}
