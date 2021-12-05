<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command\ConsoleCommandChaining;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleCommandNotChaining extends Command
{
    protected static $defaultName = 'another:hello';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello from Julia!');

        return Command::SUCCESS;
    }
}
