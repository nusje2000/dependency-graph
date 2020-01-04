<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Command;

use LogicException;
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
        $packageName = $input->getArgument('package');

        if (!is_string($packageName)) {
            throw new LogicException('Expected package argument to be a string.');
        }

        if (!$this->graph->hasPackage($packageName)) {
            $this->io->error(sprintf('Could not find package with name "%s".', $packageName));

            return 1;
        }

        $this->displayPackageInformation($this->graph->getPackage($packageName));

        return 0;
    }
}
