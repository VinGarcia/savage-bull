<?php

namespace App\Exception;

class InvalidArgumentException extends \Exception
{
    public $value;
    public $attr_name;

    public function __construct($value, $attr_name)
    {
        parent::__construct("Invalid value for `$attr_name` attribute: `$value`");

        $this->attr_name = $attr_name;
        $this->value = $value;
    }
}
