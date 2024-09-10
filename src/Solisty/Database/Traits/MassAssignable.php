<?php

namespace Solisty\Database\Traits;

trait MassAssignable
{
    protected array $_assignables = [];

    protected function isAssignable(string $prop)
    {
        return isset($this->assignables[$prop]);
    }
}
