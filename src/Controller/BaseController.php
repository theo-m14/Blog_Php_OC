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

        protected function render(string $filename, array $data = []) : void
        {
            $data['user'] = $this->getUser();
            $data['appEnv'] = $_ENV['APP_ENV'];
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

        public function redirectTo(string $route,int $httpCode) : void
        {
            if($httpCode === 404){
                header('Location: /error');
            }else{
                header('Location: ' . $route,true,$httpCode);
            }
        }
    }
