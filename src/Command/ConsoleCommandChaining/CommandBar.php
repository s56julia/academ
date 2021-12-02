<?php

namespace App\Command\ConsoleCommandChaining;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandBar extends Command {

    protected static $defaultName = 'bar:hi';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hi from Bar!');
        return Command::SUCCESS;
    }
}
