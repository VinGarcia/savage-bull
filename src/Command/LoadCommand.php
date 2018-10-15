<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use App\Model\Users;

/**
 * Class LoadCommand
 * A simple command that displays hello world to the console.
 *
 * @package App\Command
 */
class LoadCommand extends Command
{
    private static $users = [];

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('app:load');
        $this->addArgument(
            'filename',
            InputArgument::REQUIRED,
            'The CSV file from where to load the users'
        );
    }

    /**
     * Execute the command from the console.
     *
     * @param InputInterface $input the input interface
     * @param OutputInterface $output the output interface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');

        $users = Users::loadFromCsv($filename);

        $output->writeln('There are ' . sizeof($users) . ' users.');
    }
}
