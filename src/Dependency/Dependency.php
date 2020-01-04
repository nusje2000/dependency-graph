<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Dependency;

final class Dependency implements DependencyInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $versionConstraint;

    /**
     * @var DependencyTypeEnum
     */
    protected $type;

    /**
     * @var bool
     */
    protected $isDev;

    public function __construct(string $name, string $versionConstraint, bool $isDev, DependencyTypeEnum $type)
    {
        $this->name = $name;
        $this->versionConstraint = $versionConstraint;
        $this->type = $type;
        $this->isDev = $isDev;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersionConstraint(): string
    {
        return $this->versionConstraint;
    }

    public function getType(): DependencyTypeEnum
    {
        return $this->type;
    }

    public function isDev(): bool
    {
        return $this->isDev;
    }
}
