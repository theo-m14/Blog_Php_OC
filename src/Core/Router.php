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
            if(str_contains($route->path,'['))
            {
                $route->path = substr($route->path,0,strpos($route->path,'[')-1);
                $temp_url = substr_count($url,'/') > 1 ? substr($url, 0, strpos($url,'/',1)) : $url;
                return preg_match("#^" . $route->path . "$#", $temp_url) && $route->method == $method;
            }else{
                return preg_match("#^" . $route->path . "$#", $url) && $route->method == $method;
            }
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
