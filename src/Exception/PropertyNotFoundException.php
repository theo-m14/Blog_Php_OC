<?php

namespace App\Exception;

class PropertyNotFoundException extends \Exception
{
    private string $className;
    private string $property;
    
    public function __construct(string $className,string $property,string $message = "Property missing")
        {
			$this->className = $className;
            $this->property = $property;
            parent::__construct($message);
        }

        public function getMoreDetail() : string
        {
            return "Property " . $this->property . " does not exist in class " . $this->className;
        }
}