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

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleCommandChaining extends Command
{
    private $decorated;
    private array $childcommand;
    private LoggerInterface $logger;

    public function __construct(CommandFooHello $decorated, array $childcommand, LoggerInterface $logger)
    {
        $this->decorated = $decorated;
        $this->childcommand = $childcommand;
        $this->logger = $logger;

        parent::__construct($this->decorated->getName());
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $buffered = new BufferedOutput();
        $nameCommand = $this->decorated->getName();

        $this->logger->info($nameCommand.' is a master command of a command chain that has registered member commands');
        $this->logger->info('Executing '.$nameCommand.' command itself first:');

        $this->decorated->execute($input, $buffered);

        $outputData = $buffered->fetch();
        $output->write($outputData);
        $this->logger->info($outputData);

        $this->logger->info('Executing '.$nameCommand.' chain members:');

        foreach ($this->childcommand as $command) {
            $arguments = [
                'command' => $command,
                'flag_visibility' => 1, ];

            $this->logger->info($command.' registered as a member of '.$nameCommand.' command chain');
            $buffered1 = new BufferedOutput();
            $this->executeSubCommand($command, $arguments, $buffered1);

            $outputData1 = $buffered1->fetch();
            $output->write($outputData1);
            $this->logger->info($outputData1);
        }

        $this->logger->info('Execution of '.$nameCommand.' chain completed.');

        return Command::SUCCESS;
    }

    private function executeSubCommand(string $name, array $parameters, OutputInterface $output)
    {
        return $this->getApplication()
        ->find($name)
        ->run(new ArrayInput($parameters), $output);
    }
}
