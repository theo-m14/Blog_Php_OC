<?php

namespace App\Controller;
use App\Entity\User;
use App\Controller\BaseController;
use App\Repository\UserRepository;

class UserController extends BaseController{
    public function Register()
    {
        $this->render('register.html.twig');
    }

    public function CreateUser(UserRepository $userRepository,$username,$mail,$password)
    {
        $hash_password = password_hash($password,PASSWORD_DEFAULT);
        $param = ["username","mail","password","role_id"];
        $user = new User($mail,$username,$hash_password,3);
        $return = $userRepository->create($user,$param);
    }
}