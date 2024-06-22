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
        $found = false;
        $this->commands->each(
            function (Command $c) use (&$found) {
                if ($c->match($this->argv)) {
                    $c->onStart([]);
                    $c->run($this->argv);
                    $found = true;
                    return $c->onExit(0);
                }
            }
        );

        if (!$found) $this->panic();
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
        printf("\n%s\n", 'Command not found');
    }
}
