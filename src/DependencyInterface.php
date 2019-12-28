<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph;

interface DependencyInterface
{
    public function getName(): string;

    public function getVersionConstraint(): string;

    public function getType(): DependencyTypeEnum;
}
