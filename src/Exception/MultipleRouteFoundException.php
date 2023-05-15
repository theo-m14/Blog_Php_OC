<?php

namespace App\Exception;

class MultipleRouteFoundException extends \Exception
{
    public function __construct(string $message = "More than 1 route found")
    {
        parent::__construct($message);
    }
}