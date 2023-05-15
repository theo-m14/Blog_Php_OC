<?php

namespace App\Exception;

class MissingArgumentException extends \Exception
{
    public function __construct(string $message = "Missing Arguments for this route")
    {
        parent::__construct($message);
    }
}