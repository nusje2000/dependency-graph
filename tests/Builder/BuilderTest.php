<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests\Builder;

use Nusje2000\DependencyGraph\Builder\Builder;
use PHPUnit\Framework\TestCase;

final class BuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $builder = new Builder();
        $builder->build(realpath(__DIR__ . '/../../example-structure'));
    }
}
