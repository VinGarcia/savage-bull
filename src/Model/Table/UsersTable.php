<?php

namespace App\Model\Table;

use App\Model\Entity\User;

class UsersTable
{
    private static $default_filename = 'users.json';

    private static $instance;
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new UsersTable(self::$default_filename);
        }

        return self::$instance;
    }

    /* * * * * Instance Methods: * * * * */

    private $users;
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->loadTable();
    }

    public function setFilename(string $filename)
    {
        $this->filename = $filename;
        $this->loadTable();
    }

    public function addUser(User $user)
    {
        $this->users[] = $user;
    }

    public function listAll()
    {
        return $this->users;
    }

    public function saveTable()
    {
        $json = json_encode(
            array_map('\App\Model\Entity\User::toArray', $this->users)
        );

        file_put_contents($this->filename, $json);
    }

    public function loadTable()
    {
        $this->users = [];

        if (!file_exists($this->filename)) {
            return;
        }

        $json_data = json_decode(
            file_get_contents($this->filename)
        );

        // If it is not a valid json:
        if ($json_data === null) {
            throw new Exception(
                "Could not load UsersTable JSON file `$this->filename`: " .
                "Invalid JSON!"
            );
        }

        foreach ($json_data as $row) {
            $this->users[] = new User((array)$row);
        }
    }

    public function count()
    {
        return sizeof($this->users);
    }
}
