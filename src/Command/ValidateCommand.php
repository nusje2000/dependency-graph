<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Command;

use Nusje2000\DependencyGraph\Validator\Rule;
use Nusje2000\DependencyGraph\Validator\RuleCollection;
use Nusje2000\DependencyGraph\Validator\Validator;
use Symfony\Component\Console\Input\InputInterface;
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

        parent::configure();
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $validator = new Validator(new RuleCollection([
            new Rule\DevelopmentOnlyRule(),
            new Rule\MissingDependencyRule(),
            new Rule\IncompatibleVersionRule(),
        ]));

        $violations = $validator->validate($this->graph);

        if ($violations->isEmpty()) {
            $this->io->success('Dependency graph is valid.');

            return 0;
        }

        foreach ($violations as $violation) {
            $this->io->writeln(sprintf('[VIOLATION] %s', $violation->getMessage()));
        }

        return 1;
    }
}
