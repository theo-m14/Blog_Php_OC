<?php

namespace App\Exception;

class MissingArgumentException extends \Exception
{
    public function __construct($message = "Missing Arguments for this route")
    {
        parent::__construct($message, "0006");
    }
}