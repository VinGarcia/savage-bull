<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use App\Exception\InvalidArgumentException;
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

        $users = self::loadUsersFromCsv($filename, $input, $output);

        $output->writeln('There are ' . sizeof($users) . ' users.');

        $outputFilename =
            $input->getArgument('output filename') ??
            self::replaceExtension($filename, 'json');

        $json = json_encode(
            array_map('\App\Model\Entity\User::toArray', $users)
        );

        file_put_contents($outputFilename, $json);
    }

    private static function loadUsersFromCsv(
        string $filename,
        InputInterface $input,
        OutputInterface $output
    ) {
        $table = array_map('str_getcsv', file($filename));
        $header = array_shift($table);

        // Normalize header names to snake case:
        $header = array_map('self::snakeCase', $header);

        $users = [];
        foreach ($table as $row) {
            $row_data = self::processTableRow($row, $header);

            $user = null;
            do {
                try {
                    $user = new User($row_data);
                } catch (InvalidArgumentException $e) {
                    $value = print_r($e->value, true);

                    $output->writeln("Invalid value for `$e->attr_name` attribute: `$value`");
                    $correction = readline(
                        "\nPlease enter a valid `$e->attr_name` " .
                        "(or press ENTER to discard this row):\n"
                    );

                    if ($correction === '') {
                        break;
                    }

                    $row_data[$e->attr_name] = $correction;
                }
            } while (!isset($user));

            if (isset($user)) {
                $users[] = $user;
            }
        }

        return $users;
    }

    private static function processTableRow($row, $header)
    {
        $attributes = [];
        for ($i = 0; $i < sizeof($row); ++$i) {
            $key = $header[$i];
            $attributes[$key] = $row[$i];
        }

        return $attributes;
    }

    /**
     * Replaces a filename extension by the given extension.
     *
     * @param string $filename
     * @param string $ext A file-extension string like '.json' or 'json'
     */
    private static function replaceExtension($filename, $ext)
    {
        if ($ext[0] !== '.') {
            $ext = '.' . $ext;
        }

        return preg_replace('/\.[^.]*$/', $ext, $filename);
    }

    /**
     * Convert any text into snake_case strings.
     *
     * e.g.:
     * - 'Joined Date' -> 'joined_date'
     * - 'JoinedDate'  -> 'joined_date'
     * - 'joinedDate'  -> 'joined_date'
     */
    private static function snakeCase($text)
    {
        return trim(
            strtolower(
                preg_replace('/(?<!^)\s*([A-Z])/', '_$1', $text)
            )
        );
    }
}
