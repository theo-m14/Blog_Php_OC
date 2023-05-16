<?php

namespace App\Core;

use App\Exception\MultipleRouteFoundException;
use App\Exception\NoRouteFoundException;

class Router
{
    private $listRoute;

    public function __construct()
    {
        $stringRoute = file_get_contents('../config/route.json');
        $this->listRoute = json_decode($stringRoute);
    }

    public function findRoute(HttpRequest $httpRequest) : Route
    {

        $url = str_replace($_ENV['BASE_PATH'],"",$httpRequest->getUrl());
        $method = $httpRequest->getMethod();
        $routeFound = array_filter($this->listRoute,function(object $route) use ($url,$method){
                return preg_match("#^" . $route->path . "$#", $url) && $route->method == $method;
        });
        $numberRoute = count($routeFound);
        if($numberRoute > 1)
        {
            throw new MultipleRouteFoundException();
        }
        else if($numberRoute == 0)
        {
            throw new NoRouteFoundException($httpRequest);
        }
        else
        {
            return new Route(array_shift($routeFound));
        }
    }
}
