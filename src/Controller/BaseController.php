<?php

namespace App\Controller;

use App\FileManager;
use Twig\Environment;
use App\Core\HttpRequest;
use Twig\Loader\FilesystemLoader;
use App\Exception\ViewNotFoundException;


    class BaseController
    {
        private HttpRequest $httpRequest;
        private Array|null $user;
        private Environment $twig;

        public function __construct(HttpRequest $httpRequest)
        {
            $this->httpRequest = $httpRequest;
            $this->user = array_key_exists('user', $_SESSION) ? $_SESSION['user'] : null;
            $this->setTwig();
        }

        protected function setTwig() : void
        {
            $loader = new FilesystemLoader(__DIR__ . '/../View/');
            // initialiser l'environement Twig
            $this->twig = new Environment($loader, [
                'debug' => true
            ]);
            $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        }

        public function setUser() : void
        {
            $this->user = $_SESSION['user'];
        }

        public function delUser() : void
        {
            $this->user = null;
        }

        public function getUser() : array|null
        {
            return $this->user;
        }

        protected function render(string $filename, Array $data = []) : void
        {
            $data['user'] = $this->getUser();
            $this->twig->display($this->httpRequest->getRoute()->getController() . '/' . $filename, $data);
        }
    }
