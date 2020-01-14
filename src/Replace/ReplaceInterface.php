<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Replace;

interface ReplaceInterface
{
    public function getPackageName(): string;

    public function getVersion(): string;
}
