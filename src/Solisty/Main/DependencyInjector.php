<?php

namespace Solisty\Main;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionObject;

class DependencyInjector
{
    protected array $bindings = [];

    // resolve an already bound class
    public function resolve($class)
    {
        if (isset($this->bindings[$class])) {
            $binding = $this->bindings[$class];

            if ($binding instanceof \Closure) {
                $result = $binding();

                if (is_string($result) && $this->bound($result)) {
                    return $this->resolve($result);
                }

                if (is_subclass_of($result, $class)) {

                    return $this->instance($result);
                }

                return $result;
            } else if (is_string($binding)) {
                return new $binding;
            }

            return $binding;
        }
        return new $class();
    }

    public function instance(string $class, $isBuiltIn = false)
    {
        try {
            $reflection = new ReflectionClass($class);
            $constructor = $reflection->getConstructor();
            if ($constructor === null) {
                return new $class();
            }
            $parameters = $constructor->getParameters();
            $dependencies = [];
            foreach ($parameters as $parameter) {
                $dependencyClass = $parameter->getType()->getName();
                if ($dependencyClass !== null && $this->bound($dependencyClass)) {
                    $dependencies[] = $this->resolve($dependencyClass);
                } else {
                    $key = $parameter->getName();
                    if ($this->bound($key)) {
                        $dependencies[] = $this->resolve($key);
                    } else
                        throw new Exception("Unable to resolve dependency for parameter '{$parameter->name}' in class '$class'");
                }
            }
            return $reflection->newInstanceArgs($dependencies);
        } catch (ReflectionException $err) {
            ppd($err);
        }
    }

    public function bound(string $class)
    {
        return isset($this->bindings[$class]);
    }

    public function classExists(string $class)
    {
        return class_exists($class);
    }

    public function bindCallback(string $class, callable $callback)
    {
        $this->bindings[$class] = $callback;
    }

    public function bind($key, $value)
    {
        $this->bindings[$key] = $value;

        return $this;
    }

    public function bindArray($pairs)
    {
        foreach ($pairs as $key => $value) {
            $this->bindings[$key] = $value;
        }

        return $this;
    }

    // add a string shortcut to the last added binding
    public function shortcut(string $shortcut)
    {
        if ($this->bound($shortcut)) return;
        $keys = array_keys($this->bindings);
        $last = end($keys);
        $this->bindings[$shortcut] = $this->bindings[$last];

        // if ()
    }

    public function call($callback, array $dependencies = [])
    {
        $reflection = is_array($callback) ? new ReflectionMethod($callback[0], $callback[1]) : new ReflectionFunction($callback);
        $parameters = $reflection->getParameters();

        $resolvedDependencies = [];
        foreach ($parameters as $parameter) {
            $dependencyClass = $parameter->getType() ? $parameter->getType()->getName() : null;
            if ($dependencyClass !== null && isset($dependencies[$dependencyClass])) {
                $resolvedDependencies[] = $dependencies[$dependencyClass];
            } elseif ($dependencyClass !== null) {
                $resolvedDependencies[] = $this->resolve($dependencyClass);
            } elseif (isset($dependencies[$parameter->name])) {
                $resolvedDependencies[] = $dependencies[$parameter->name];
            } elseif ($parameter->isDefaultValueAvailable()) {
                $resolvedDependencies[] = $parameter->getDefaultValue();
            } else {
                throw new Exception("Unable to resolve dependency for parameter '{$parameter->name}'");
            }
        }

        // Determine the object to invoke the method on
        $object = is_array($callback) ? $this->resolve($callback[0]) : null;

        return $reflection->invokeArgs($object, $resolvedDependencies);
    }

    public function resolveIfImplements($class, $interface)
    {
        if ($this->bound($class))
            $reflection = new ReflectionClass($class);

        if ($reflection->implementsInterface($interface)) {
            return $this->resolve($class);
        }

        return null;
    }

    public function resolveIfParent($class, $parent)
    {
        $reflection = new ReflectionClass($class);

        if ($reflection->isSubclassOf($parent)) {
            return $this->resolve($class);
        }

        return null;
    }

    public function resolveIfChild($class, $child)
    {
        $reflection = new ReflectionClass($class);

        if ($reflection->isSubclassOf($child)) {
            return $this->resolve($class);
        }

        return null;
    }

    public function resolveIfHasMethod($class, $method)
    {
        $reflection = new ReflectionClass($class);

        if ($reflection->hasMethod($method)) {
            return $this->resolve($class);
        }

        return null;
    }
}
