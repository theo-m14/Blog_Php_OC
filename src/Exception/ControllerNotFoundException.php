<?php

namespace App\Exception;

class ControllerNotFoundException extends \Exception
{
    public function __construct(string $message = "Controller not found for this route")
    {
        parent::__construct($message);
    }
}
