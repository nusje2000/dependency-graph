<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Validator\Violation;

use Nusje2000\DependencyGraph\Dependency\DependencyInterface;
use Nusje2000\DependencyGraph\Package\PackageInterface;
use Nusje2000\DependencyGraph\Validator\ViolationInterface;

final class IncompatibleVersionConstraintViolation implements ViolationInterface
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
            'Dependency on "%s" in package "%s" requires version that matches "%s".',
            $this->dependency->getName(),
            $this->package->getName(),
            $this->dependency->getVersionConstraint()
        );
    }
}
