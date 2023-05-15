<?php

namespace App\Entity;

use App\Exception\PropertyNotFoundException;

class User{
    private ?string $mail;
    private ?string $username;
    private ?string $password;
    private mixed $role_id;

    public function __construct(string $mail = null,string $username = null ,string $password = null,mixed $role_id = null)
    {
        $this->mail = $mail;
        $this->username = $username;
        $this->password = $password;
        $this->role_id = $role_id;
    }


    public function getMail() : string
    {
        return $this->mail;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getRole() : mixed
    {
        return $this->role_id;
    }

    public function setRole(mixed $role) : void
    {
        $this->role_id = $role;
    }

    public function getUsername() : string
    {
        return $this->username;
    }
}
