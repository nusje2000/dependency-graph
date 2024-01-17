<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Command;

use Nusje2000\DependencyGraph\Package\PackageCollection;
use Nusje2000\DependencyGraph\Package\PackageInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SearchCommand extends AbstractDependencyGraphCommand
{
    protected static $defaultName = 'search';

    protected function configure(): void
    {
        $this->setDescription('Search through all the dependencies resolved by the dependency-graph and display information out it.');

        parent::configure();
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        if (!$input->isInteractive()) {
            $this->io->error('This is an interactive command, if you want just the package information, use the info command.');

            return 1;
        }

        $package = $this->searchForPackage();
        $this->displayPackageInformation($package);
        $this->waitForEnterKey();

        return $this->doExecute($input, $output);
    }

    private function searchForPackage(): PackageInterface
    {
        $searchedPackage = $this->io->ask('What package do you want to lookup ? (TIP: you could also enter a part of a package name)');
        assert(is_string($searchedPackage));
        $suggestedPackages = $this->suggestPackages((string)$searchedPackage);

        if ($suggestedPackages->isEmpty()) {
            $this->io->error(sprintf('Could not find any pacakge with name "%s".', $searchedPackage));

            return $this->searchForPackage();
        }

        $firstPackage = $suggestedPackages->first();
        if (1 === $suggestedPackages->count() && null !== $firstPackage) {
            return $firstPackage;
        }

        $options = ['Search again'];
        $options = array_merge($options, array_values(
            $suggestedPackages->map(static function (PackageInterface $package): string {
                return $package->getName();
            })
        ));

        $choice = $this->io->choice('What package would you like to view ?', $options);
        assert(is_string($choice));

        if (null !== $choice && 'Search again' !== $choice) {
            return $this->graph->getPackage($choice);
        }

        return $this->searchForPackage();
    }

    private function suggestPackages(string $search): PackageCollection
    {
        if (empty($search)) {
            return $this->graph->getPackages();
        }

        return $this->graph->getPackages()->filter(static function (PackageInterface $package) use ($search): bool {
            return false !== stripos($package->getName(), $search);
        });
    }
}
