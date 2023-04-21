<?php

namespace App\Exception;

class ControllerNotFoundException extends \Exception
{
    public function __construct($message = "Controller not found for this route")
    {
        parent::__construct($message, "0005");
    }
}