<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Author;

interface AuthorInterface
{
    public function getName(): string;

    public function getEmail(): ?string;

    public function getHomepage(): ?string;

    public function getRole(): ?string;
}
