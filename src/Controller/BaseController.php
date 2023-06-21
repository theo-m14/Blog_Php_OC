<?php

namespace App\Controller;

use App\Core\Router;
use App\FileManager;
use Twig\Environment;
use App\Core\HttpRequest;
use Twig\Loader\FilesystemLoader;
use App\Exception\ViewNotFoundException;


    class BaseController
    {
        private HttpRequest $httpRequest;
        private array|null $user;
        private Environment $twig;
        private ?string $error;

        public function __construct(HttpRequest $httpRequest)
        {
            $this->httpRequest = $httpRequest;
            $this->user = array_key_exists('user', $_SESSION) ? $_SESSION['user'] : null;
            $this->error = null;
            if(array_key_exists('error', $_SESSION)){
                $this->error = $_SESSION['error'];
                unset($_SESSION['error']);
            }
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

        public function getError() : ?string
        {
            return $this->error;
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

        protected function render(string $filename, array $data = []) : void
        {
            $data['user'] = $this->getUser();
            $data['appEnv'] = $_ENV['APP_ENV'];
            $data['error'] = $this->getError();
            $this->twig->display($this->httpRequest->getRoute()->getController() . '/' . $filename, $data);
        }

        public function isGranted(object $object) : bool
        {
            if (!$this->getUser()) {
                return false;
            }
            if ($this->getUser()['role'] == "admin" || (method_exists($object, "getUserId") && $this->getUser()['id'] == $object->getUserId() )) {
                return true;
            }
            return false;
        }

        public function isAdmin() : bool
        {
            if (!$this->getUser()) {
                return false;
            }
            if ($this->getUser()['role'] == "admin") {
                return true;
            }
            return false;
        }

        public function redirectTo(string $route,int $httpCode,?string $error = null) : void
        {
            if($httpCode === 404){
                header('Location: /error');
            }else{
                $_SESSION['error'] = $error;
                header('Location: ' . $route,true,$httpCode);
            }
        }
    }
