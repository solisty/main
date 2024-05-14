<?php

namespace Solisty\CommandLine\Traits;

use Solisty\CommandLine\Process;
use Solisty\String\Path;

trait CaptureOutput
{
    public const MAX_BATCH_READ = 1024;
    protected ?Process $process = null;

    public function capture()
    {
        while (!feof($this->process->{$this->getStreamType()}()) || $this->process->running()) {
            $line = fgets($this->process->{$this->getStreamType()}(), self::MAX_BATCH_READ);
            if ($line) {
                $this->onRead($line);
            }
        }
    }

    public function onRead(string $content)
    {
        echo $content;
    }

    public function getStreamType()
    {
        return "stdout";
    }
}
