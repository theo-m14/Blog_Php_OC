<?php

namespace App\Controller;
use App\Entity\User;
use App\Controller\BaseController;
use App\Repository\UserRepository;

class UserController extends BaseController{
    public function Login()
    {
        if($this->getUser()){
            header('Location: /');
        }else{
            $this->render("login.html.twig");
        }
    }

    public function Authenticate(UserRepository $userRepository,$login,$password)
    {
        $user = $userRepository->getByUsername($login);
        if($user and $password == password_verify($password,$user->getPassword())){
            $_SESSION['user'] = [];
            $_SESSION['user']['username'] = $user->getUsername();
            $_SESSION['user']['role'] = $user->getRole();
            $this->setUser($_SESSION['user']);
            header('Location: /');
        }else{
            $this->render("login.html.twig", ['error' => "Identifiant incorrect"]);
        }
    }

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
        header('Location: /Login');
    }

    public function Logout()
    {
        $this->setUser(null);
        session_destroy();
        header('Location: /');
    }
}