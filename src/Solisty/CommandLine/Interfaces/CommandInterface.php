<?php

namespace Solisty\CommandLine\Interfaces;

interface CommandInterface {
    public function name();
    public function getArgs();
    public function run(array $args);
    public function subCommands();
    public function onExit($statusCode);
    public function onStart(array $env);
}