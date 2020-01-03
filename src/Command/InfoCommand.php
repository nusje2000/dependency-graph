<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class InfoCommand extends AbstractDependencyGraphCommand
{
    protected static $defaultName = 'info';

    protected function configure(): void
    {
        $this->setDescription('Show information about a package.');
        $this->addArgument('package', InputArgument::REQUIRED, 'The package you want information for.');

        parent::configure();
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $packages = $this->graph->getPackages();
        $packageName = $input->getArgument('package');

        if (!$packages->hasPackageByName($packageName)) {
            $this->io->error(sprintf('Could not find package with name "%s".', $packageName));

            return 1;
        }

        $this->displayPackageInformation($packages->getPackageByName($packageName));

        return 0;
    }
}
