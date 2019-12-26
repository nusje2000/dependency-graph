<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Builder;

use Nusje2000\DependencyGraph\DependencyGraph;

interface GraphBuilderInterface
{
    /**
     * Build a dependency graph for the given root path
     */
    public function build(string $rootPath): DependencyGraph;
}
