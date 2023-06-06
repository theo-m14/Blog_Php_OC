<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\BaseController;
use App\Repository\UserRepository;

class UserController extends BaseController
{
    public function login() : void
    {
        if ($this->getUser()) {
            $this->redirectTo('/');
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
            $this->setUser();
            $this->redirectTo('/');
        }else {
            $this->render("login.html.twig", ['error' => "Identifiant incorrect"]);
        }
    }

    public function register() : void
    {
        $this->render('register.html.twig');
    }

    public function createUser(UserRepository $userRepository, string $username, string $mail, string $password) : void
    {
        //Verif valid email
        $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($emailPattern, $mail)) {
            $this->render("login.html.twig", ['error' => "Veuillez saisir une adresse mail valide"]);
            return;
        }
        if ($userRepository->userExistByField("mail", $mail)) {
            $this->render("login.html.twig", ['error' => "Cette adresse mail est déjà utilisé"]);
            return;
        }
        //Verif exist user by username
        if ($userRepository->userExistByField("username", $username)) {
            $this->render("login.html.twig", ['error' => "Ce pseudonyme n'est pas disponible"]);
            return;
        }
        //Verif valid password
        $passwordPattern = '/^(?=.*[\d])[a-zA-Z0-9]{6,}$/';
        if (!preg_match($passwordPattern, $password)) {
            $this->render(
                "login.html.twig",
                ['error' => "Votre mot de passe doit contenir au moins 6 caractères dont un chiffre"]
                );
        }

        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $param = ["username","mail","password","role_id"];
        $user = new User($mail, $username, $hashPassword, 3);
        $userRepository->insert($user, $param);
        $this->redirectTo('/login');
    }

    public function logout() : void
    {
        $this->delUser();
        session_destroy();
        $this->redirectTo('/');
    }
}
