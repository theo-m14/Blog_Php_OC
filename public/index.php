<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\HttpRequest;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '../../config');
$dotenv->load();

   session_start();
    try {
        $httpRequest = new HttpRequest();
        $router = new Router();
        $httpRequest->setRoute($router->findRoute($httpRequest));
        $httpRequest->run();
    } catch (Exception $e) {
        $httpRequest = new HttpRequest("/Error", "GET");
        $router = new Router();
        $httpRequest->setRoute($router->findRoute($httpRequest));
        $httpRequest->addParam($e);
        $httpRequest->run();
    }
