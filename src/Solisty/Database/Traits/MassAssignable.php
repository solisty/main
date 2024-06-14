<?php

namespace Solisty\Database\Traits;

trait MassAssignable
{
    protected array $assignables = [];

    protected function isAssignable(string $prop)
    {
        return isset($this->assignables[$prop]);
    }
}
