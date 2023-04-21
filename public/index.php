<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\HttpRequest;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '../../config');
$dotenv->load();

    try{
        $httpRequest = new HttpRequest();
        $router = new Router();
    }catch(Exception $e){
        print($e->getMessage());
    }