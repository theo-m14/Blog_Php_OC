<?php

namespace App\Controller;

use App\Controller\BaseController;

class HomeController extends BaseController
{
    public function home() : void
    {
        $this->render("home.html.twig");
    }

    public function sendEmail(string $name,string $email,string $content) : void
    {
        if(strlen($name) < 2)
        {
            $error = "Votre nom doit faire plus de 2 caratères";

            $this->render("home.html.twig", ["contactError" => $error]);
            return;
        }

        if(strlen($content) < 10)
        {
            $error = "Votre message doit faire plus de 10 caratères";

            $this->render("home.html.twig", ["contactError" => $error]);
            return;
        }

        $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($emailPattern, $email)) {
            $this->render("home.html.twig", ['contactError' => "Veuillez saisir une adresse mail valide"]);
            return;
        }

        $to      = $_ENV['MAIL'];
        $subject = 'Demande de contact de ' . $name;
        $message = 'Email de contact : ' . $email . "\r\n" . $content;
        $headers = 'From: ' .$email . "\r\n".
                    'Reply-To: ' . $email. "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

        if(mail($to,$subject,$message,$headers))
        {
            $this->render("home.html.twig", ['success' => "Demande de contact envoyé !"]);
        }else{
            $this->render("home.html.twig", ['contactError' => "Une erreur est survenu"]);
        }
    }
}
