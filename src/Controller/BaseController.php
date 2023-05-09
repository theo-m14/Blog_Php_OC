<?php

namespace App\Controller;

use App\FileManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Exception\ViewNotFoundException;


    class BaseController
    {
        private $httpRequest;
        private $user;
        private $twig;

        public function __construct($httpRequest)
        {
            $this->httpRequest = $httpRequest;
            $this->user = array_key_exists('user', $_SESSION) ? $_SESSION['user'] : null;
            $this->setTwig();
        }

        protected function setTwig()
        {
            $loader = new FilesystemLoader(__DIR__ . '/../View/');
            // initialiser l'environement Twig
            $this->twig = new Environment($loader, [
                'debug' => true
            ]);
            $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        }

        public function setUser()
        {
            $this->user = $_SESSION['user'];
        }

        public function getUser()
        {
            return $this->user;
        }

        protected function render($filename, Array $data = [])
        {
            $data['user'] = $this->getUser();
            return $this->twig->display($this->httpRequest->getRoute()->getController() . '/' . $filename, $data);
        }
    }