<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Command;

use LogicException;
use Nusje2000\DependencyGraph\DependencyCollection;
use Nusje2000\DependencyGraph\DependencyInterface;
use Nusje2000\DependencyGraph\PackageInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ValidateCommand extends AbstractDependencyGraphCommand
{
    protected static $defaultName = 'validate';

    protected function configure(): void
    {
        $this->setDescription(
            'Validate definition files within the project. This is supposed to be used within monolithic repositories to make sure' .
            'that all dependencies defined by sub-packages are present in the root package definition.'
        );

        $this->addOption('autofix', 'f', InputOption::VALUE_NONE, 'Attempt to fix the composer file when definitions are missing.');

        parent::configure();
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $rootPackage = $this->graph->getRootPackage();
        $autofix = (bool)$input->getOption('autofix');

        if (!$input->isInteractive() && $autofix) {
            $this->io->error('When using the autofix option, sometimes user input is required if it cannot be fixed automatically.');

            return 1;
        }

        // ignore vendor packages
        $subPackages = $this->graph->getPackages()->filter(static function (PackageInterface $package) use ($rootPackage): bool {
            return !$package->isFromVendor() && $package !== $rootPackage;
        });

        $subPackageDependencies = $subPackages->getDependencies();
        foreach ($this->getDependencyNames($subPackageDependencies) as $dependencyName) {
            $isDevOnlyDependency = $this->isDevOnlyDependency($subPackageDependencies, $dependencyName);
            $versionConstraints = $this->getVersionConstraints($subPackageDependencies, $dependencyName);

            if ($rootPackage->hasDependency($dependencyName)) {
                $dependency = $rootPackage->getDependency($dependencyName);

                if (!$isDevOnlyDependency && $dependency->isDev()) {
                    $this->io->writeln(sprintf(
                        '[ERROR] dependency on "%s" is dev-only but is used in subpackage as non-dev dependency.',
                        $dependencyName
                    ));

                    if ($autofix) {
                        $this->fixDependency($dependencyName, [$dependency->getVersionConstraint()], false);
                    }
                }

                continue;
            }

            $this->io->writeln(sprintf(
                '[ERROR] missing dependency on "%s" (version constraints: [%s], dev only: %s)',
                $dependencyName,
                implode(', ', $versionConstraints),
                $isDevOnlyDependency ? 'true' : 'false'
            ));

            if ($autofix) {
                $this->fixDependency($dependencyName, $versionConstraints, $isDevOnlyDependency);
            }
        }

        return 0;
    }

    /**
     * @return array<string>
     */
    private function getDependencyNames(DependencyCollection $dependencies): array
    {
        return array_unique($dependencies->map(static function (DependencyInterface $dependency): string {
            return $dependency->getName();
        }));
    }

    /**
     * @return array<string>
     */
    private function getVersionConstraints(DependencyCollection $dependencies, string $dependencyName): array
    {
        return array_unique(
            $dependencies->filterByName($dependencyName)->map(static function (DependencyInterface $dependency): string {
                return $dependency->getVersionConstraint();
            })
        );
    }

    private function isDevOnlyDependency(DependencyCollection $missingDependencies, string $dependencyName): bool
    {
        return $missingDependencies->filterByName($dependencyName)->filter(static function (DependencyInterface $dependency) {
            return $dependency->isDev();
        })->isEmpty();
    }

    /**
     * @param array<string> $versionConstraints
     */
    private function fixDependency(string $dependencyName, array $versionConstraints, bool $isDevOnly): void
    {
        $this->io->writeln(sprintf('[INFO] Attempting to fix dependency on "%s".', $dependencyName));

        $versionConstraint = reset($versionConstraints);
        if (count($versionConstraints) > 1) {
            $versionConstraint = $this->io->ask(sprintf(
                'What would you like the version constraint to be (found constraints: [%s]) ?',
                implode(', ', $versionConstraints)
            ));
        }

        $this->saveDependencyToRootPackage($dependencyName, $versionConstraint, $isDevOnly);
    }

    private function saveDependencyToRootPackage(string $dependencyName, string $versionConstraint, bool $isDev): void
    {
        $rootPackage = $this->graph->getRootPackage();
        $composerFile = $rootPackage->getPackageLocation() . DIRECTORY_SEPARATOR . 'composer.json';

        if (!file_exists($composerFile)) {
            throw new LogicException(sprintf('File "%s" does not exist.', $composerFile));
        }

        $composerDefinition = json_decode(file_get_contents($composerFile), true);

        if ($isDev) {
            if (isset($composerDefinition['require'][$dependencyName])) {
                $this->io->writeln(sprintf('[INFO] removing dependency on "%s" from require.', $dependencyName));
                unset($composerDefinition['require'][$dependencyName]);
            }

            $this->io->writeln(sprintf('[INFO] adding dependency on "%s" to require-dev.', $dependencyName));
            $composerDefinition['require-dev'][$dependencyName] = $versionConstraint;
        } else {
            if (isset($composerDefinition['require-dev'][$dependencyName])) {
                $this->io->writeln(sprintf('[INFO] removing dependency on "%s" from require-dev.', $dependencyName));
                unset($composerDefinition['require-dev'][$dependencyName]);
            }

            $this->io->writeln(sprintf('[INFO] adding dependency on "%s" to require.', $dependencyName));
            $composerDefinition['require'][$dependencyName] = $versionConstraint;
        }

        $newComposerDefinition = json_encode($composerDefinition, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents($composerFile, $newComposerDefinition);
    }
}
