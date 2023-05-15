<?php

namespace App\Controller;
use App\Entity\User;
use App\Controller\BaseController;
use App\Repository\UserRepository;

class UserController extends BaseController{
    public function Login() : void 
    {
        if($this->getUser()){
            header('Location: /');
        }else{
            $this->render("login.html.twig");
        }
    }

    public function Authenticate(UserRepository $userRepository,string $login, string $password) : void
    {
        $user = $userRepository->getByUsername($login);
        if($user and $password == password_verify($password,$user->getPassword())){
            $_SESSION['user'] = [];
            $_SESSION['user']['username'] = $user->getUsername();
            $_SESSION['user']['role'] = $user->getRole();
            $this->setUser();
            header('Location: /');
        }else{
            $this->render("login.html.twig", ['error' => "Identifiant incorrect"]);
        }
    }

    public function Register() : void
    {
        $this->render('register.html.twig');
    }

    public function CreateUser(UserRepository $userRepository,string $username,string $mail,string $password) : void 
    {
        $hash_password = password_hash($password,PASSWORD_DEFAULT);
        $param = ["username","mail","password","role_id"];
        $user = new User($mail,$username,$hash_password,3);
        $userRepository->insert($user,$param);
        header('Location: /Login');
    }

    public function Logout() : void
    {
        $this->delUser();
        session_destroy();
        header('Location: /');
    }
}