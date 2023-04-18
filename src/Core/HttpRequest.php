<?php

namespace App\Core;

class HttpRequest
{
    private $url;
    private $method;
    private $param;
    private $route;

    public function __construct($url = null, $method = null)
    {
        $this->url = (is_null($url)) ? $_SERVER['REQUEST_URI'] : $url;
        $this->method = (is_null($method)) ? $_SERVER['REQUEST_METHOD'] : $method;
        $this->param = array();
    }

    /**
     * Get the value of param
     */ 
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Get the value of method
     */ 
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the value of url
     */ 
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the value of _route
     */ 
    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function bindParam()
    {
    }

    /**
     * Get the value of _route
     */ 
    public function getRoute()
    {
        return $this->route;
    }

    
    public function addParam($value)
    {
        $this->param[]= $value;
    }
}