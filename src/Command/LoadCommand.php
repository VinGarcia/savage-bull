<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use App\Model\Entity\User;

/**
 * Class LoadCommand
 * A simple command that displays hello world to the console.
 *
 * @package App\Command
 */
class LoadCommand extends Command
{
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

        $this->addArgument(
            'output filename',
            InputArgument::OPTIONAL,
            'The JSON file where the users should be saved'
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

        $users = User::loadFromCsv($filename);

        // Read output filename from input or use the
        // input filename with a json extension:
        $outputFilename =
            $input->getArgument('output filename') ??
            self::replaceExtension($filename, 'json');

        User::saveAsJson($users, $outputFilename);

        $output->writeln('There are ' . sizeof($users) . ' users.');
    }

    private static function replaceExtension($filename, $ext)
    {
        if ($ext[0] !== '.') {
            $ext = '.' . $ext;
        }

        return preg_replace('/\.[^.]*$/', $ext, $filename);
    }
}
