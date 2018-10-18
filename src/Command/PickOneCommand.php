<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use App\Exception\InvalidArgumentException;
use App\Model\Entity\User;

/**
 * Class PickOneCommand
 * A simple command that displays hello world to the console.
 *
 * @package App\Command
 */
class PickOneCommand extends Command
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('pick-one');
        $this->addArgument(
            'filename',
            InputArgument::REQUIRED,
            'The JSON file from where to read the users'
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

        $users = self::loadUsersFromJson($filename, $input, $output);

        $output->writeln('There are ' . sizeof($users) . ' users.');

        $output->writeln("\nRandomizing a user...\n");

        $len = sizeof($users);
        $selected = $users[rand(0, $len)];
        echo json_encode(User::toArray($selected), JSON_PRETTY_PRINT) . "\n\n";
    }

    private static function loadUsersFromJson(
        string $filename,
        InputInterface $input,
        OutputInterface $output
    ) {
        $json_data = json_decode(
            file_get_contents($filename)
        );

        $users = [];
        foreach ($json_data as $row) {
            $users[] = new User((array)$row);
        }

        return $users;
    }
}
