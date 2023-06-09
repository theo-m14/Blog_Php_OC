<?php

namespace App\Exception;

class WrongArgumentsTypeException extends \Exception
{
    public function __construct(string $message = "Wrong function arguments")
    {
        parent::__construct($message);
    }
}
