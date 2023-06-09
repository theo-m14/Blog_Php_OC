<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\BaseController;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;

class UserController extends BaseController
{
    public function login() : void
    {
        if ($this->getUser()) {
            $this->redirectTo('/',303);
        }else {
            $this->render("login.html.twig");
        }
    }

    public function authenticate(UserRepository $userRepository, string $login, string $password) : void
    {
        $user = $userRepository->getByMail($login);
        if ($user && $password == password_verify($password, $user->getPassword())) {
            $_SESSION['user'] = [];
            $_SESSION['user']['mail'] = $user->getMail();
            $_SESSION['user']['username'] = $user->getUsername();
            $_SESSION['user']['role'] = $user->getRole();
            $_SESSION['user']['id'] = $user->getId();
            $_SESSION['user']['csrfToken'] = bin2hex(random_bytes(32));
            $this->setUser();
            $this->redirectTo('/',302);
        }else {
            $this->render("login.html.twig", ['error' => "Identifiant incorrect"]);
        }
    }

    public function register() : void
    {
        $this->render('register.html.twig');
    }

    public function createUser(UserRepository $userRepository,string $username,string $mail,string $password) : void
    {
        $error = $this->verifUserRegister($userRepository,$username,$mail,$password);

        if(!empty($error)){
            $this->render('register.html.twig',["error" => $error]);
            return;
        }

        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $param = ["username","mail","password","role_id"];
        $user = new User($mail, $username, $hashPassword, 3);
        $userRepository->insert($user, $param);
        $this->redirectTo('/login',302);
    }

    public function verifUserRegister(UserRepository $userRepository,string $username,string $mail,string $password) : array
    {
        $error = [];
        $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($emailPattern, $mail)) {
            $error['email'] = "Veuillez saisir une adresse mail valide";
        }
        if ($userRepository->userExistByField("mail", $mail)) {
            $error['email'] = "Cette adresse mail est déjà utilisé";
        }
        //Verif exist user by username
        if ($userRepository->userExistByField("username", $username)) {
            $error['username'] = "Ce pseudonyme n'est pas disponible";
        }

        if(strlen($username) < 4){
            $error['username'] = "Votre pseudonyme doit faire au moins 4 caractères";
        }

        //Verif valid password
        $passwordPattern = '/^(?=.*[\d])[a-zA-Z0-9]{6,}$/';
        if (!preg_match($passwordPattern, $password)) {
                $error['password'] = "Votre mot de passe doit contenir au moins 6 caractères dont un chiffre";
        }

        return $error;
    }

    public function logout() : void
    {
        $this->delUser();
        session_destroy();
        $this->redirectTo('/',302);
    }
}
