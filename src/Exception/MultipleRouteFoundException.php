<?php

namespace App\Exception;

class MultipleRouteFoundException extends \Exception
{
    public function __construct($message = "More than 1 route found")
    {
        parent::__construct($message, "0001");
    }
}