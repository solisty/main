<?php

namespace Solisty\Main\Interfaces;

use Solisty\Http\Request;

interface ApplicationInterface
{
    public function __construct(array $env, bool $debug);
    public function setEnv(array $env);
    public function handle(Request $request);
}
