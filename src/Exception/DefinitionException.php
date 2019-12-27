<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Exception;

use LogicException;
use Nusje2000\DependencyGraph\Composer\PackageDefinition;

final class DefinitionException extends LogicException implements ExceptionInterface
{
    public static function missingNameDefinition(string $pathToComposerFile): self
    {
        return new static(sprintf('Missing "name" in package definition (composer file: %s).', $pathToComposerFile));
    }

    public static function unresolvableReference(string $packageReference, PackageDefinition $referencedBy): self
    {
        return new static(sprintf(
            'The pacakge "%s" was referenced by "%s" but could not be resolved (origin: %s)',
            $packageReference,
            $referencedBy->getName(),
            $referencedBy->getSource()
        ));
    }

    public static function duplicatePackageDefinition(PackageDefinition $existing, PackageDefinition $new): self
    {
        return new static(sprintf(
            'Package named "%s" is defined more than once (first: %s, second: %s).',
            $new->getName(),
            $existing->getSource(),
            $new->getSource()
        ));
    }
}
