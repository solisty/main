<?php

namespace Solisty\Database;

use Exception;
use Solisty\Database\Traits\MassAssignable;

class Model extends Queryable
{
    // use HasQueries;
    // use HasIndex;
    // use Paginated;
    // use Duplicatable;
    use MassAssignable;

    protected array $relations;
    private array $propreties = [];

    public function save()
    {
    }

    public function new(array $props)
    {
    }

    public function getProperties()
    {
        return $this->propreties;
    }

    public function setProperty($name, $value)
    {
        $this->propreties[$name] = $value;
    }

    protected function saving()
    {
        echo 'saving model';
    }

    public static function fromResult($results)
    {
        $callingClass = static::class;
        $object = new $callingClass;

        $object->propreties = $results;

        return $object;
    }

    public function __get($prop)
    {
        if (isset($this->propreties[$prop])) {
            return $this->propreties[$prop];
        } else {
            throw new Exception("Undefined properity '$prop' on model: " . static::class);
        }
    }

    public function assign(string $prop, $value): bool
    {
        if ($this->isAssignable($prop)) {
            $this->propreties[$prop] = $value;
            return true;
        }

        return false;
    }

    public static function __callStatic($name, $arguments)
    {
        switch ($name) {
            case 'create':
                return static::insert($arguments[0]);
            default:
                throw new Exception("Method $name does not exist on model: " . static::class);
        }
    }

    public function __call($name, $arguments)
    {
        return $this;
    }

    public function __set($name, $value)
    {
        if (isset($this->getProperties()[$name])) {
            $this->setProperty($name, $value);
        }
    }
}
