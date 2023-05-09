<?php

namespace App\Entity;

use App\Exception\PropertyNotFoundException;

class User{
    private $id;
    private $mail;
    private $username;
    private $password;
    private $role_id;

    public function __construct($mail = null,$username = null ,$password = null,$role_id = null)
    {   
        $this->mail = $mail;
        $this->username = $username;
        $this->password = $password;
        $this->role_id = $role_id;
    }

    public function generalGetter($paramName)
    {
        if(property_exists($this,$paramName))
			{
				return $this->$paramName;	
			}
			else
			{
				throw new PropertyNotFoundException($this->$this,$paramName);	
			}
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRole()
    {
        return $this->role_id;
    }

    public function setRole(string $role)
    {
        $this->role_id = $role;
    }

    public function getUsername()
    {
        return $this->username;
    }
}