<?php

namespace App\Model;

class Users
{
    private static $users = [];

    public const ATTRIBUTES = [
        'id',
        'first_name',
        'email',
        'country',
        'latitude',
        'longitude',
        'date_joined',
    ];

    public static function fromArray($data, $header = ATTRIBUTES)
    {
        if (sizeof($header) != sizeof(self::ATTRIBUTES)) {
            throw new Exception('Bad number of columns on header!');
        }

        if (sizeof($data) < sizeof(self::ATTRIBUTES)) {
            throw new Exception('Bad number of columns on user data!');
        }

        $user = [];
        for ($i = 0; $i < sizeof($data); ++$i) {
            $key = self::ATTRIBUTES[$i];
            $user[$key] = $data[$i];
        }

        $user['id'] = (int)$user['id'];

        $user['first_name'] = (string)$user['first_name'];
        $user['email'] = (string)$user['email'];
        $user['country'] = (string)$user['country'];

        $user['latitude'] = (float)$user['latitude'];
        $user['longitude'] = (float)$user['longitude'];

        $user['date_joined'] = new \DateTime($user['date_joined']);

        return $user;
    }

    public static function loadFromCsv($filename)
    {
        $data = array_map('str_getcsv', file($filename));
        $header = array_shift($data);

        // Normalize names to snake case:
        $header = array_map('self::snakeCase', $header);

        foreach ($data as $row) {
            self::$users[] = self::fromArray($row, $header);
        }

        return self::$users;
    }

    private static function snakeCase($text)
    {
        return trim(
            strtolower(
                preg_replace('/(?<!^)\s*([A-Z])/', '_$1', $text)
            )
        );
    }
}
