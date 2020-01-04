<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Validator;

interface ViolationInterface
{
    public function getMessage(): string;
}
