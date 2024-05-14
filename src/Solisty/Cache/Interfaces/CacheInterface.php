<?php

namespace Solisty\Cache\Interfaces;

use Solisty\List\ArrayList;

interface CacheInterface
{
    public function set($key, $value);

    public function get($key);

    public function remove($key): bool;

    public function has($key): bool;

    public function clear(): ArrayList;

    public function empty(): bool;

    public static function getDriverName(): string;
}
