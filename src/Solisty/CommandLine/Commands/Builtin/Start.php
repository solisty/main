<?php

namespace Solisty\CommandLine\Commands\Builtin;

use Solisty\CommandLine\Commands\Command;
use Solisty\CommandLine\Process;
use Solisty\CommandLine\Traits\CaptureOutput;
use Solisty\CommandLine\Traits\Colors;

class Start extends Command
{
    use CaptureOutput, Colors;

    public function __construct()
    {
        $this->process = new Process();
        $this->name = "start";
    }

    public function run(array $argv)
    {
        $cmd = $this->getServeCmd();
        $this->process->open($cmd, []);
        $this->capture();
    }

    private function getServeCmd()
    {
        return 'php -S localhost:8888 -t public/';
    }

    public function onStart(array $env)
    {
        echo "\v\v{$this->colorize('Server Started', 'yellow')}\v {$this->colorize('http://localhost:8888', 'blue')}\n\n";
    }

    public function onRead(string $line)
    {
        if (strpos($line, 'Development Server') !== false) {
            return;
        }

        $line = preg_replace('/(GET|POST|PUT|DELETE|PATCH|OPTIONS|HEAD)/', $this->colorize('$1', 'yellow'), $line);
        echo $line;
    }

    // called when cmd is done running
    public function onExit($statusCode)
    {
        $this->process->terminate();
        pp('done');
    }

    public function getStreamType()
    {
        // php webserver writes to stderr
        return "stderr";
    }
}
