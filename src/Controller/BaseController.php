<?php

namespace App\Controller;

use App\FileManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Exception\ViewNotFoundException;


    class BaseController
    {
        private $httpRequest;
        private $twig;

        public function __construct($httpRequest)
        {
            $this->httpRequest = $httpRequest;
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

        protected function render($filename, Array $data = [])
        {
            return $this->twig->display($this->httpRequest->getRoute()->getController() . '/' . $filename, $data);
        }
    }