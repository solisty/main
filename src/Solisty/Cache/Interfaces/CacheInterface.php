<?php

namespace Solisty\Cache\Interfaces;

interface CacheInterface
{
    // cache a file
    public function cacheFile($filePath);

    // cache a string
    public function cacheContent($key, string $content);

    // read all cache files and keys for easy access
    public function prepare(array $keys);

    // load all cache into memery
    public function loadAll(): array;

    // check if key is cached
    public function cached($key);

    // check if content is a file otherwire a string
    public function tmp($key, $content);

    // load a cache entry as json
    public function loadJson($key);
}
