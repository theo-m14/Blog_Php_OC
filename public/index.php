<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\HttpRequest;

    try{
        $httpRequest = new HttpRequest();
        $router = new Router();
    }catch(Exception $e){
        print($e->getMessage());
    }