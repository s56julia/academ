<?php

namespace App\Command\ConsoleCommandChaining;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandFooHello extends Command {

    protected static $defaultName = 'foo:hello';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello from Foo!');
        return Command::SUCCESS;
    }
}
