<?php

namespace App\Model\Entity;

use App\Exception\InvalidArgumentException;

class User
{
    public $id;
    public $first_name;
    public $email;
    public $country;
    public $latitude;
    public $longitude;
    public $date_joined;

    public function __construct(Array $attributes)
    {
        $this->id = (int)$attributes['id'];

        $this->first_name = (string)$attributes['first_name'];

        if (!filter_var($attributes['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException($attributes['email'], 'email');
        }

        $this->email = (string)$attributes['email'];
        $this->country = (string)$attributes['country'];

        $this->latitude = (float)$attributes['latitude'];
        $this->longitude = (float)$attributes['longitude'];

        $this->date_joined = new \DateTime($attributes['date_joined']);
    }

    public static function toArray(User $user)
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

    /**
     * Convert DateTime/Date objects into ISO8601 strings
     */
    private static function dateToString($date)
    {
        return $date->format(\DateTime::ATOM);
    }
}
