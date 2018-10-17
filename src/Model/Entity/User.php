<?php

namespace App\Model\Entity;

class User
{
    public const ATTRIBUTES = [
        'id',
        'first_name',
        'email',
        'country',
        'latitude',
        'longitude',
        'date_joined',
    ];

    public $id;
    public $first_name;
    public $email;
    public $country;
    public $latitude;
    public $longitude;
    public $date_joined;

    public static function fromDataRow($data, $header = ATTRIBUTES)
    {
        if (sizeof($header) != sizeof(self::ATTRIBUTES)) {
            throw new Exception('Bad number of columns on header!');
        }

        if (sizeof($data) < sizeof(self::ATTRIBUTES)) {
            throw new Exception('Bad number of columns on user data!');
        }

        $attributes = [];
        for ($i = 0; $i < sizeof($data); ++$i) {
            $key = self::ATTRIBUTES[$i];
            $attributes[$key] = $data[$i];
        }

        $user = new User;

        $user->id = (int)$attributes['id'];

        $user->first_name = (string)$attributes['first_name'];
        $user->email = (string)$attributes['email'];
        $user->country = (string)$attributes['country'];

        $user->latitude = (float)$attributes['latitude'];
        $user->longitude = (float)$attributes['longitude'];

        $user->date_joined = new \DateTime($attributes['date_joined']);

        return $user;
    }

    public static function toArray($user)
    {
        $array = [];

        $array['id'] = $user->id;

        $array['first_name'] = $user->first_name;
        $array['email'] = $user->email;
        $array['country'] = $user->country;

        $array['latitude'] = $user->latitude;
        $array['longitude'] = $user->longitude;

        $array['date_joined'] = self::dateToString($user->date_joined);

        return $array;
    }

    public static function toJson($users)
    {
        if (!is_array($users)) {
            return json_encode(self::toArray($users));
        } else {
            return json_encode(
                array_map('self::toArray', $users)
            );
        }
    }

    /**
     * Convert DateTime/Date objects into ISO8601 strings
     */
    private static function dateToString($date)
    {
        return $date->format(\DateTime::ATOM);
    }
}
