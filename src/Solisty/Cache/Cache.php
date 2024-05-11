<?php

namespace Solisty\Cache;

use Solisty\Cache\Drivers\File\Cache as FileCache;

class Cache
{
    protected $driver = null;

    public function __construct()
    {
        $this->driver = new FileCache('');
    }

    public function getDriverClassPath() {

    }
}
