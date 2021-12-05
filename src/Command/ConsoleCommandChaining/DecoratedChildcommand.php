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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DecoratedChildcommand extends Command
{
    private $decorated;
    private string $command;

    public function __construct(CommandBar $decorated, string $command)
    {
        $this->decorated = $decorated;
        $this->command = $command;

        parent::__construct($this->decorated->getName());
    }

    public function configure(): void
    {
        $this->decorated->configure();
        $this->addArgument('flag_visibility', InputArgument::OPTIONAL, 'flag visibility');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $flagVisibility = $input->getArgument('flag_visibility');

        if (isset($flagVisibility) && 1 === $flagVisibility) {
            $this->decorated->execute($input, $output);
        } else {
            $output->writeln('Error: '.$this->decorated->getName().' command is a member of '.$this->command.' command chain and cannot be executed on its own.');
        }

        return Command::SUCCESS;
    }
}
