<?php

namespace Solisty\CommandLine\Commands;

use Solisty\CommandLine\Interfaces\CommandInterface;
use Solisty\CommandLine\Process;

abstract class Command implements CommandInterface
{
    public $name;

    protected ?Process $process = null;

    public function __construct()
    {
    }

    public function match(array $argv)
    {
        return $this->name == $argv[1];
    }

    public function run(array $args)
    {
    }

    public function name()
    {
    }

    public function getArgs()
    {
    }

    public function subCommands()
    {
    }
}
