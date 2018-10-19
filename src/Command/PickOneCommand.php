<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
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

        $this->addOption(
            'country-code',
            'c',
            InputOption::VALUE_OPTIONAL,
            'Country from where to pick the user',
            ''
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
        $country_code = strtoupper($input->getOption('country-code'));

        $users = self::loadUsersFromJson($filename);

        $message = 'There are ' . sizeof($users) . ' users';

        if ($country_code !== '') {
            $users = self::filterByCountry($users, $country_code);

            $message = $message . " from `$country_code`";
        }

        $output->writeln($message . '.');

        if (sizeof($users) === 0) {
            exit(0);
        }

        $output->writeln("\nRandomizing a user...\n");

        $len = sizeof($users);
        $selected = $users[rand(0, $len)];
        echo json_encode(User::toArray($selected), JSON_PRETTY_PRINT) . "\n\n";
    }

    private static function loadUsersFromJson(string $filename)
    {
        $json_data = json_decode(
            file_get_contents($filename)
        );

        $users = [];
        foreach ($json_data as $row) {
            $users[] = new User((array)$row);
        }

        return $users;
    }

    private static function filterByCountry($users, $country)
    {
        $filtered_users = [];
        foreach ($users as $user) {
            if ($user->country === $country) {
                $filtered_users[] = $user;
            }
        }

        return $filtered_users;
    }
}
