<?php

namespace Solisty\CommandLine;

class Process
{
    protected bool $isClosed = true;
    protected array $pipes = [];
    protected array $specs = [
        ["pipe", "r"],
        ["pipe", "w"],
        ["pipe", "w"]
    ];

    public function __construct(protected $handle = null)
    {
    }

    public function open($cmd, $options)
    {
        $this->handle = proc_open($cmd, $this->specs, $this->pipes, null, null, $options);
        $this->isClosed = false;
    }

    public function close()
    {
        if (is_resource($this->handle)) {
            proc_close($this->handle);
            $this->isClosed = true;
        }
    }

    public function terminate()
    {
        if (is_resource($this->handle)) {
            proc_terminate($this->handle);
            $this->close();
        }
    }

    public function running()
    {
        if (!$this->isClosed) {
            return proc_get_status($this->handle)['running'];
        }

        return false;
    }

    public function &stdin()
    {
        return $this->pipes[0];
    }

    public function &stdout()
    {
        return $this->pipes[1];
    }

    public function &stderr()
    {
        return $this->pipes[2];
    }
}
