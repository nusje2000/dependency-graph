<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Author;

final class Author implements AuthorInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $homepage;

    /**
     * @var string|null
     */
    private $role;

    public function __construct(string $name, ?string $email = null, ?string $homepage = null, ?string $role = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->homepage = $homepage;
        $this->role = $role;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }
}
