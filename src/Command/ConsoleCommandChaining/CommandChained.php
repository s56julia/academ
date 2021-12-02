<?php

namespace App\Command\ConsoleCommandChaining;

use Symfony\Component\Console\Command\Command;

class ConsoleCommandChaining extends Command {
    
    private string $parentcommand;
    private array $childcommand;
    
    public function __construct(string $parentcommand, array $childcommand)
    {
        $this->parentcommand = $parentcommand;
        $this->childcommand = $childcommand;

        parent::__construct();
    }

 
}
