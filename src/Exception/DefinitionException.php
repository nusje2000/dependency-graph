<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Exception;

use LogicException;

final class DefinitionException extends LogicException implements ExceptionInterface
{
    public static function missingNameDefinition(string $pathToComposerFile): self
    {
        return new static(sprintf('Missing "name" in package definition (composer file: %s).', $pathToComposerFile));
    }

    public static function duplicatePackageDefinition(string $name): self
    {
        return new static(sprintf('Package named "%s" is defined more than once.', $name));
    }
}
