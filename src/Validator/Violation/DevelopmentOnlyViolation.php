<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Validator\Violation;

use Nusje2000\DependencyGraph\Dependency\DependencyInterface;
use Nusje2000\DependencyGraph\Package\PackageInterface;
use Nusje2000\DependencyGraph\Validator\ViolationInterface;

final class DevelopmentOnlyViolation implements ViolationInterface
{
    /**
     * @var PackageInterface
     */
    protected $package;

    /**
     * @var DependencyInterface
     */
    protected $dependency;

    public function __construct(PackageInterface $package, DependencyInterface $dependency)
    {
        $this->package = $package;
        $this->dependency = $dependency;
    }

    public function getMessage(): string
    {
        return sprintf(
            'Dependency "%s" is used by package "%s" as non-dev dependency but the package is defined as dev dependency in the root.',
            $this->dependency->getName(),
            $this->package->getName()
        );
    }
}
