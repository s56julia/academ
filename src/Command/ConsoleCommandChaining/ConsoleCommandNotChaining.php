<?php

namespace App\Command\ConsoleCommandChaining;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleCommandNotChaining extends Command {

    protected static $defaultName = 'another:hello';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello from Julia!');
        return Command::SUCCESS;
    }
}
