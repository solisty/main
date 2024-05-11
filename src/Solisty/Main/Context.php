<?php

namespace Solisty\Main;

use Solisty\Http\Request;
use Solisty\Http\Response;
use Solisty\Main\Interfaces\ContextInterface;

abstract class Context implements ContextInterface
{
    protected DependencyInjector $injector;
    protected Env $env;

    public function makeResponse(Request $request): Response
    {
        return new Response;
    }

    public function setEnv(array $env)
    {
        $this->env = new Env($env);
    }

    public function scopedEnv(string $scope, Env $env) {

    }

    public function retrieve($key) {
        return $this->injector->resolve($key);
    }

    public function call(string $class, string $method) {
        $this->injector->call([$class, $method]);
    }
}
