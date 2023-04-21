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
        $url = $httpRequest->getUrl();
        $method = $httpRequest->getMethod();
        $routeFound = array_filter($this->listRoute,function($route) use ($url,$method){
            if(str_contains($route->path,'['))
                return preg_match("#^" . $route->path . "$#", $url) && $route->method == $method;
        });
        $numberRoute = count($routeFound);
        if($numberRoute > 1)
        {
            //Implement Exception
            print("Too many Route found");
        }
        else if($numberRoute == 0)
        {
            //Implement Exception
            print("No route found");
        }
        else
        {
            return new Route(array_shift($routeFound));	
        }
    }
}