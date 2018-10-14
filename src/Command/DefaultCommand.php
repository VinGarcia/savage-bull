<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DefaultCommand
 * A simple command that displays hello world to the console.
 *
 * @package App\Command
 */
class DefaultCommand extends Command
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('app:hello-world');
    }

    /**
     * Execute the command from the console.
     *
     * @param InputInterface $input the input interface
     * @param OutputInterface $output the output interface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Hello World!');
    }
}
