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

        $url = str_replace($_ENV['BASE_PATH'], "", $httpRequest->getUrl());
        $method = $httpRequest->getMethod();
        $routeFound = array_filter($this->listRoute, function(object $route) use ($url,$method){
            if (str_contains($route->path,'[')) {
                return $this->sameGetPath($url,$route,$method);
            }else {
                return preg_match("#^" . $route->path . "$#", $url) && $route->method == $method;
            }
        });
        $numberRoute = count($routeFound);
        if ($numberRoute > 1)
        {
            throw new MultipleRouteFoundException();
        }
        elseif ($numberRoute == 0)
        {
            throw new NoRouteFoundException($httpRequest);
        }
        else
        {
            return new Route(array_shift($routeFound));
        }
    }


    public function sameGetPath(string $url,object $route,string $method) : bool
    {
        $path =  explode('/', $route->path);
        $tempUrl = explode('/', $url);

        if(count($path) != count($tempUrl)){
            return false;
        }

        foreach ($path as $key => $value) {
            if (str_contains($value, '[')) {
                $tempUrl[$key] = $value;
            }
        }

        if($tempUrl == $path && $route->method == $method)
        {
            $route->path = substr($route->path,0,strpos($route->path,'[')-1);
            return true;
        }

        return false;
    }
}
