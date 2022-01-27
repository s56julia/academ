<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\EntityGenerator\EntityGenerator;

class DataLoadCommand extends Command {

    protected static $defaultName = 'data:load';
    protected static $defaultDescription = 'Data load Product and ProductImage';

    private EntityGenerator $generator;

    public function __construct(string $name = null, EntityGenerator $generator)
    {
        parent::__construct($name);
        $this->generator = $generator;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to add data Product and ProductImage');

        $this->addOption('products-count', null, InputOption::VALUE_REQUIRED, 'Enter the number of Products', 500);
        $this->addOption('product-images-count', null, InputOption::VALUE_REQUIRED, 'Enter the number of Images', 100);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $productsCount = $input->getOption('products-count');
        $productImagesCount = $input->getOption('product-images-count');

        if($productsCount <0 || $productImagesCount < 0)
        {
            $output->writeln('Invalid value products-count or product-images-count');
            return Command::INVALID;
        }

        $this->generator->generateEntity($productsCount, $productImagesCount);

        return Command::SUCCESS;
    }
}
