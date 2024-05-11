<?php

namespace Solisty\Main;

use Solisty\List\HashList;
use Dotenv\Dotenv;

class Env extends HashList
{
    public function __construct(array $env)
    {
        $dotenv = Dotenv::createImmutable($env['APP_BASE']);
        $dotenv->load();
        
        foreach($env as $key => $value) {
            $this->add($key, $value);
        }

        foreach($_ENV as $key => $value) {
            $this->add($key, $value);
        }
    }
}
