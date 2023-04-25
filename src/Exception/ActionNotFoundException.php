<?php

namespace App\Exception;

class ActionNotFoundException extends \Exception
{
    public function __construct($message = "Action not found for this route")
    {
        parent::__construct($message, "0003");
    }

}