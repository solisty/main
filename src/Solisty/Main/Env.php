<?php

namespace Solisty\Main;

use Solisty\List\HashList;

class Env extends HashList
{
    public function __construct(array $env)
    {
        foreach($env as $key => $value) {
            $this->add($key, $value);
        }
    }
}
