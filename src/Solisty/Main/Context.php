<?php

namespace Solisty\Main;

use Solisty\Http\Request;
use Solisty\Http\Response;
use Solisty\Main\Interfaces\ContextInterface;

abstract class Context implements ContextInterface
{
    protected DependencyInjector $injector;
    protected Env $env;
    protected $controllerValue = null;

    public function makeResponse(Request $request): Response
    {
        if ($this->controllerValue !== null) {
            return new Response($this->controllerValue);
        }

        // simply return empty response if the controller didn't provide a value
        return new Response();
    }

    public function setEnv(array $env)
    {
        $this->env = new Env($env);
    }

    public function scopedEnv(string $scope, Env $env)
    {
    }

    public function retrieve($key)
    {
        return $this->injector->resolve($key);
    }

    public function call(string $class, string $method)
    {
        return $this->injector->call([$class, $method]);
    }
}
