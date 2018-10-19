<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use App\Exception\InvalidArgumentException;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;

/**
 * Class AddUsersCommand
 * A simple command that displays hello world to the console.
 *
 * @package App\Command
 */
class AddUsersCommand extends Command
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('add-users');

        $this->addArgument(
            'filename',
            InputArgument::REQUIRED,
            'The JSON file from where to read and write the users'
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
        UsersTable::instance()
            ->setFilename($filename);

        $numUsers = UsersTable::instance()->count();

        $output->writeln("There are $numUsers users.");

        do {
            $one_more = strtolower(
                readline("\nDo you want to add another user? (Y/n)\n")
            );

            if ($one_more === '') {
                $one_more = 'y';
            }

            if ($one_more[0] === 'n') {
                break;
            }

            if ($one_more[0] !== 'y') {
                $output->writeln("Please answer with 'yes' or 'no'\n");
                continue;
            }

            try {
                UsersTable::instance()
                    ->addUser(
                        self::promptNewUser($output)
                    );
            } catch (InvalidArgumentException $e) {
                $output->writeln("\nFailed to create user:");
                $output->writeln(
                    "  invalid value received for the user `$e->attr_name`"
                );
                continue;
            }

            UsersTable::instance()
                ->saveTable();
        } while (true);
    }

    /**
     * Build an user by prompting the user for each attribute.
     *
     * @throws if any of the provided values is invalid.
     *
     * @return User
     */
    private static function promptNewUser(OutputInterface $output)
    {
        $attrs = [
            'id',
            'first_name',
            'email',
            'country',
            'latitude',
            'longitude',
            'date_joined',
        ];

        $output->writeln("Please enter each of the user attributes below:\n");

        $user_data = [];
        foreach ($attrs as $key) {
            $user_data[$key] = readline("  $key: ");
        }

        // Might throw an error:
        return new User($user_data);
    }
}
