<?php

namespace App\Core;


class Router
{
    private $listRoute;
    
    public function __construct()
    {
        $stringRoute = file_get_contents('../config/route.json');
        $this->listRoute = json_decode($stringRoute);
    }
    
    public function findRoute(HttpRequest $httpRequest)
    {
    }
}