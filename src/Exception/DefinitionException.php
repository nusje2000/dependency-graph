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
}
