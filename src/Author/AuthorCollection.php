<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Author;

use Aeviiq\Collection\ImmutableObjectCollection;
use ArrayIterator;
use Closure;
use Nusje2000\DependencyGraph\Exception\AuthorException;

/**
 * @phpstan-extends ImmutableObjectCollection<int|string, AuthorInterface>
 * @psalm-extends   ImmutableObjectCollection<int|string, AuthorInterface>
 *
 * @method ArrayIterator|AuthorInterface[] getIterator()
 * @method AuthorInterface|null first()
 * @method AuthorInterface|null last()
 * @method AuthorCollection filter(Closure $closure)
 */
final class AuthorCollection extends ImmutableObjectCollection
{
    public function getAuthorByEmail(string $email): AuthorInterface
    {
        $author = $this->filterByEmail($email)->first();

        if (null === $author) {
            throw AuthorException::notFoundByEmail($email);
        }

        return $author;
    }

    public function hasAuthorByEmail(string $name): bool
    {
        return !$this->filterByEmail($name)->isEmpty();
    }

    public function getAuthorByName(string $name): AuthorInterface
    {
        $author = $this->filterByName($name)->first();

        if (null === $author) {
            throw AuthorException::notFoundByName($name);
        }

        return $author;
    }

    public function hasAuthorByName(string $name): bool
    {
        return !$this->filterByName($name)->isEmpty();
    }

    protected function allowedInstance(): string
    {
        return AuthorInterface::class;
    }

    private function filterByEmail(string $email): self
    {
        return $this->filter(static function (AuthorInterface $author) use ($email): bool {
            return $author->getEmail() === $email;
        });
    }

    private function filterByName(string $name): self
    {
        return $this->filter(static function (AuthorInterface $author) use ($name): bool {
            return $author->getName() === $name;
        });
    }
}
