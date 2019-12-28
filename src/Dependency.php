<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

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

    public function __construct(string $name, string $versionConstraint, DependencyTypeEnum $type)
    {
        $this->name = $name;
        $this->versionConstraint = $versionConstraint;
        $this->type = $type;
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
}
