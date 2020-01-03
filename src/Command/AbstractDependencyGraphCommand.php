<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Command;

use Nusje2000\DependencyGraph\Cache\FileCache;
use Nusje2000\DependencyGraph\DependencyGraph;
use Nusje2000\DependencyGraph\DependencyInterface;
use Nusje2000\DependencyGraph\PackageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractDependencyGraphCommand extends Command
{
    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @var DependencyGraph
     */
    protected $graph;

    protected function configure(): void
    {
        $this->addOption('clear-cache', null, InputOption::VALUE_NONE, 'Clear the cache before building the graph.');
    }

    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $projectRoot = getcwd();

        $cache = new FileCache();
        if ($input->getOption('clear-cache')) {
            $cache->remove($projectRoot);
        }

        $this->graph = DependencyGraph::build($projectRoot, null, $cache);

        return $this->doExecute($input, $output);
    }

    abstract protected function doExecute(InputInterface $input, OutputInterface $output): int;

    protected function displayPackageInformation(PackageInterface $package): void
    {
        $this->io->success(sprintf('Found information about package "%s".', $package->getName()));
        $this->io->writeln(sprintf('Location: %s', $package->getPackageLocation()));

        $this->io->newLine();

        $this->io->section('Dependencies');
        $this->io->table(
            ['Name', 'Type', 'Version'],
            $package->getDependencies()->map(static function (DependencyInterface $dependency): array {
                return [$dependency->getName(), $dependency->getType()->getValue(), $dependency->getVersionConstraint()];
            })
        );

        $dependingPackages = $this->graph->getPackages()->filterByDependency($package->getName());
        if (!$dependingPackages->isEmpty()) {
            $this->io->newLine();
            $this->io->section('Packages depending on this package');
            $this->io->table(
                ['Name', 'Required version'],
                $this->graph->getPackages()->filterByDependency($package->getName())->map(
                    static function (PackageInterface $dependingPackage) use ($package): array {
                        $dependencyDefinition = $dependingPackage->getDependencies()->getDependencyByName($package->getName());

                        return [$dependingPackage->getName(), $dependencyDefinition->getVersionConstraint()];
                    }
                )
            );
        }

        $this->io->newLine();
    }

    protected function waitForEnterKey(): void
    {
        $this->io->write('press enter to continue...');
        fgets(STDIN);
        $this->io->newLine();
    }
}
