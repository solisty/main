<?php

namespace Solisty\CommandLine;

use Solisty\CommandLine\Commands\Command;
use Solisty\FileSystem\Directory;
use Solisty\List\HashList;
use Solisty\String\Str;

class CommandLine
{

    public HashList $commands;
    protected $commandsLoaded = false;

    public function __construct(protected array $argv)
    {
        $this->commands = new HashList();
        $this->loadBuiltInCommands();
    }

    public function run()
    {
        $this->commands->each(
            function (Command $c) {
                if ($c->match($this->argv)) {
                    $c->onStart([]);
                    $c->run($this->argv);
                    return $c->onExit(0);
                }

                $this->panic();
            }
        );
    }

    public function loadBuiltInCommands()
    {
        $cmdFiles = Directory::ls(__DIR__ . '/./Commands/Builtin');

        foreach ($cmdFiles as $file) {
            if ($file != '.' && $file != '..') {
                $class = Str::split($file, '.')[0];
                $class = new ("\\Solisty\\CommandLine\\Commands\\Builtin\\" . $class);
                $cmds[] = $class;
            }
        }

        // add to list and use name as key
        $this->commands->addObjects('name', $cmds);
        $this->commandsLoaded = true;
    }

    public function panic()
    {
        echo 'Command not found';
    }
}
