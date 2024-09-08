<?php

namespace Solisty\Main;

use Solisty\Http\Request;
use Solisty\Http\Response;
use Solisty\List\HashList;
use Solisty\Main\Interfaces\ContextInterface;

abstract class Context implements ContextInterface
{
    protected DependencyInjector $globalScope;
    protected HashList $scopes;
    protected Env $env;
    protected $controllerValue = null;

    public function __construct()
    {
        $this->globalScope = new DependencyInjector();
        $this->scopes = new HashList();
    }

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
        return $this->globalScope->resolve($key);
    }

    public function callControllerMethod(string $class, string $method, array $params = [])
    {
        return $this->globalScope->call([$class, $method], $params);
    }

    protected function bindGlobalScope($key, $value)
    {
        $this->globalScope->bind($key, $value);
    }

    protected function bindOn($key, $value, $scope = 'global')
    {
        if ($scope === 'global') {
            $this->bindGlobalScope($key, $value);
        } else {
            if (!$this->scopes->has($scope)) {
                $this->scopes->add($scope, new DependencyInjector());
            }

            $this->scopes->get($scope)->bind($key, $value);
        }
    }

    public function on($scope): ?DependencyInjector
    {
        if ($this->scopes->has($scope)) {
            return $this->scopes->get($scope);
        }

        $injector = new DependencyInjector();
        // create a new scope
        $this->scopes->add($scope, $injector);
        return $injector;
    }
}
