<?php

namespace Solisty\CommandLine\Commands\Builtin;

use Solisty\CommandLine\Commands\Command;
use Solisty\CommandLine\Process;
use Solisty\CommandLine\Traits\CaptureOutput;
use Solisty\CommandLine\Traits\CaptureStdout;

class Start extends Command
{
    use CaptureOutput;

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
        //
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
