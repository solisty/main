<?php

namespace Solisty\Cache\Drivers\File;

use Solisty\Cache\Interfaces\CacheInterface;

class Cache implements CacheInterface
{
    private $cacheDirectory;

    public function __construct(string $cacheDirectory)
    {
        $this->cacheDirectory = rtrim($cacheDirectory, '/') . '/';
        if (!is_dir($this->cacheDirectory)) {
            mkdir($this->cacheDirectory, 0777, true);
        }
    }

    public function cacheFile($filePath)
    {
        $fileName = basename($filePath);
        copy($filePath, $this->cacheDirectory . $fileName);
        return $fileName;
    }

    public function cacheContent($key, string $content, int $duration = null)
    {
        $fileName = uniqid('cache_') . '.txt';
        file_put_contents($this->cacheDirectory . $fileName, $content);
        if ($duration !== null) {
            touch($this->cacheDirectory . $fileName, time() + $duration);
        }
        return $fileName;
    }

    public function prepare(array $keys)
    {
        $cachedFiles = [];
        foreach ($keys as $key) {
            $cachedFiles[$key] = $this->cacheDirectory . $key;
        }
        return $cachedFiles;
    }

    public function loadAll(): array
    {
        $files = scandir($this->cacheDirectory);
        $cachedData = [];
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $cachedData[$file] = file_get_contents($this->cacheDirectory . $file);
            }
        }
        return $cachedData;
    }

    public function cached($key)
    {
        return file_exists($this->cacheDirectory . $key);
    }

    public function remove($key)
    {
        if ($this->cached($key)) {
            unlink($this->cacheDirectory . $key);
        }
    }

    public function flush()
    {
        $files = glob($this->cacheDirectory . '*');
        foreach ($files as $file) {
            unlink($file);
        }
    }

    public function tmp($key, $content)
    {
        return is_file($this->cacheDirectory . $key) ? $this->cacheDirectory . $key : $content;
    }

    public function loadJson($key)
    {
        $jsonData = file_get_contents($this->cacheDirectory . $key);
        return json_decode($jsonData, true);
    }
}
