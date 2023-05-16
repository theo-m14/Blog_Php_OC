<?php

namespace App\Core;

use App\Core\Route;

class HttpRequest
{
    private string $url;
    private string $method;
    private array $param;
    private Route $route;

    public function __construct(string $url = null,string $method = null)
    {
        $this->url = (is_null($url)) ? $_SERVER['REQUEST_URI'] : $url;
        $this->method = (is_null($method)) ? $_SERVER['REQUEST_METHOD'] : $method;
        $this->param = array();
    }

    /**
     * Get the value of param
     */
    public function getParam() : array
    {
        return $this->param;
    }

    /**
     * Get the value of method
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Get the value of url
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * Set the value of _route
     */
    public function setRoute(Route $route) : void
    {
        $this->route = $route;
    }

    public function bindParam() : void
    {
        switch($this->method)
        {
            case "GET":
            case "DELETE":
                    $params = $this->getParamFromUrl();
                    foreach($params as $param){
                            $this->param[] = $param;
                    }
            case "POST":
            case "PUT":
                if(!empty($this->route->getParam()))
                {
                    foreach($this->route->getParam() as $param){
                        if(isset($_POST[$param])){
                                $this->param[] = $_POST[$param];
                        }
                    }
                }
        }
    }

    /**
     * Get the value of _route
     */
    public function getRoute() : Route
    {
        return $this->route;
    }


    public function addParam(mixed $value) :void
    {
        $this->param[]= $value;
    }

    public function getParamFromUrl() : array
    {
        $indexOfParams = strlen($this->route->getPath()) + 1;
        $params = [];
        if(strlen(substr($this->url,$indexOfParams)) > 0){
            $params = explode('/',substr($this->url,$indexOfParams));
        }
        return $params;
    }

    public function run() : void
    {
        $this->bindParam();
        $this->route->run($this);
    }

    public function clearParam() :void
    {
        $this->param = [];
    }
}
