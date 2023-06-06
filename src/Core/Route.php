<?php

namespace App\Core;

use Exception;
use ReflectionClass;
use ReflectionParameter;
use ReflectionUnionType;
use App\Exception\ActionNotFoundException;
use App\Exception\MissingArgumentException;
use App\Exception\ControllerNotFoundException;
use App\Exception\WrongArgumentsTypeException;

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
            if (class_exists($controllerName)) {

                $controller = new $controllerName($httpRequest);
                if (method_exists($controller, $this->action)) {
                    $testParameters = new \ReflectionMethod($controller,$this->action);
                    $requiredParametersNumber = $testParameters->getNumberOfRequiredParameters();
                    if (count($httpRequest->getParam()) < $requiredParametersNumber) {
                        try{
                            $this->autoBindArguments(
                                $testParameters->getParameters(),
                                $httpRequest,
                                $requiredParametersNumber);
                        } catch(Exception $e) {
                            throw new MissingArgumentException();
                        }
                    }

                    $parameters = $testParameters->getParameters();

                    if(!$this->validArguments($httpRequest,$parameters))
                    {
                        throw new WrongArgumentsTypeException;
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

    public function getType(ReflectionParameter $reflectionParameter) : ?object
    {
        $reflectionType = $reflectionParameter->getType();

        if (!$reflectionType) {
            return null;
        }

        return $reflectionType instanceof ReflectionUnionType
            ? $reflectionType->getTypes()[0]
            : $reflectionType;
    }

    public function validArguments($httpRequest,$parameters) : bool
    {
        foreach($parameters as $key => $parameter)
        {
            $param = $this->performAutoCast($httpRequest->getParam()[$key],$this->getType($parameter)->getName());
            if(!$this->isTypeOf($param,$this->getType($parameter)->getName()))
            {
                return false;
            }
        }
        return true;
    }

    public function isTypeOf(mixed $object,string $typeName) : bool
    {
        switch ($typeName) {
            case 'int':
                $result = is_int($object);
                break;
            case 'float':
                $result = is_float($object);
                break;
            case 'string':
                $result = is_string($object);
                break;
            case 'bool':
                $result = is_bool($object);
                break;
            // Ajoutez d'autres cas pour les types que vous souhaitez gérer
            default:
                $reflectionClass = new ReflectionClass($typeName);
                $result = $reflectionClass->isInstance($object);
        }
        return $result;
    }

    public function performAutoCast($value, $type) {
        switch ($type) {
            case 'int':
                $result = (int) $value;
                if($result === 0){
                    $result = $value;
                }
                break;
            case 'float':
                $result = (float) $value;
                if($result === 0.0){
                    $result = $value;
                }
                break;
            case 'string':
                $result = (string) $value;
                break;
            default:
                $result = $value;
                break;
        }

        return $result;
    }


    public function autoBindArguments(array $parameters, HttpRequest $httpRequest, int $requiredParametersNumber) : void
    {
        $tempParams = $httpRequest->getParam();
        $httpRequest->clearParam();
        foreach ($parameters as $parameter) {
            if (!in_array($parameter->getName(), $httpRequest->getParam())) {
                $className = $parameter->getType()->getName();
                $newParameters = new $className();
                $httpRequest->addParam($newParameters);
            }
            if ((count($httpRequest->getParam()) + count($tempParams)) == $requiredParametersNumber) {
                break;
            }
        }
        foreach ($tempParams as $param) {
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
