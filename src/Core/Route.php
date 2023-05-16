<?php

namespace App\Core;

use Exception;
use App\Controller\BaseController;
use App\Exception\ActionNotFoundException;
use App\Exception\MissingArgumentException;
use App\Exception\ControllerNotFoundException;

class Route{

    private string $path;
    private string $controller;
    private string $action;
    private string $method;
    private array|string $param;

    public function __construct(mixed $route)
    {
        $this->path = $route->path;
        $this->controller = $route->controller;
        $this->action = $route->action;
        $this->method = $route->method;
        $this->param = $route->param;
    }

    public function run(HttpRequest $httpRequest) : void
    {
        $controller = null;
			$controllerName = 'App\Controller\\' . $this->controller . "Controller";
            if(class_exists($controllerName))
            {

                $controller = new $controllerName($httpRequest);
                if(method_exists($controller, $this->action))
                {
                    $testParameters = new \ReflectionMethod($controller,$this->action);
                    $requiredParametersNumber = $testParameters->getNumberOfRequiredParameters();
                    if(count($httpRequest->getParam()) < $requiredParametersNumber){
                        try{
                            $this->autoBindArguments($testParameters->getParameters(),$httpRequest,$requiredParametersNumber);
                        }catch(Exception $e){
                            throw new MissingArgumentException();
                        }
                    }
                    $controller->{$this->action}(...$httpRequest->getParam());
                }
                else
                {
                    throw new ActionNotFoundException();
                }
            }
            else
            {
                throw new ControllerNotFoundException();
            }
    }


    public function autoBindArguments(array $parameters,HttpRequest $httpRequest,int $requiredParametersNumber) : void
    {
        $temp_params = $httpRequest->getParam();
        $httpRequest->clearParam();
        foreach($parameters as $parameter){
            if(!in_array($parameter->getName(), $httpRequest->getParam())){
                $className = $parameter->getType()->getName();
                $new_parameters = new $className();
                $httpRequest->addParam($new_parameters);
            }
            if((count($httpRequest->getParam()) + count($temp_params)) == $requiredParametersNumber){
                break;
            }
        }
        foreach($temp_params as $param){
            $httpRequest->addParam($param);
        }
    }
    /**
     * Get the value of path
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * Get the value of controller
     */
    public function getController() : string
    {
        return $this->controller;
    }

    /**
     * Get the value of action
     */
    public function getAction() : string
    {
        return $this->action;
    }

    /**
     * Get the value of method
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Get the value of param
     */
    public function getParam() : array|string
    {
        return $this->param;
    }
}
