<?php

namespace App\Core;

class Route{
    
    private $path;
    private $controller;
    private $action;
    private $method;
    private $param;

    public function __construct($route)
    {
        $this->path = $route->path;
        $this->controller = $route->controller;
        $this->action = $route->action;
        $this->method = $route->method;
        $this->param = $route->param;
    }

    public function run($httpRequest)
    {
        $controller = null;
			$controllerName = 'App\Controller\\' . $this->controller . "Controller";
            if(class_exists($controllerName))
            {
				
                $controller = new $controllerName($httpRequest);
                if(method_exists($controller, $this->action))
                {
                    $testParameters = new \ReflectionMethod($controller,$this->action);
                    if(count($httpRequest->getParam()) < $testParameters->getNumberOfRequiredParameters()){
                        //Implement Exception
                        print("Missing arguments");
                    }
                    $controller->{$this->action}(...$httpRequest->getParam());
                }
                else
                {
                    //Implement Exception
                    print("Action not found");
                }
            }
            else
            {
                //Implement Exception
                print("Controller not found");;
            }
    }
    /**
     * Get the value of path
     */ 
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the value of controller
     */ 
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Get the value of action
     */ 
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get the value of method
     */ 
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the value of param
     */ 
    public function getParam()
    {
        return $this->param;
    }
}