<?php

namespace Solisty\Cache\Drivers\File;

use DateTime;
use Dotenv\Exception\InvalidPathException;
use Solisty\Cache\Interfaces\CacheInterface;
use Solisty\FileSystem\Directory;
use Solisty\FileSystem\File;
use Solisty\List\ArrayList;
use Solisty\String\Str;

class Cache implements CacheInterface
{
    private Directory $cacheDir;
    private File $currentFile;

    public function __construct(string $path = "")
    {
        if (empty($path)) {
            $path = app('path.app') . '/vault/cache';
        }

        $this->cacheDir = new Directory();
        $this->cacheDir->open($path);

        if (!$this->cacheDir->isOpen()) {
            throw new InvalidPathException("Cache: Invalid cache path");
        }

        $this->setOpenFile();
    }

    public function setOpenFile()
    {
        $last = new File();
        $lastTimestamp = 0;
        // open the most recent cache file
        foreach ($this->cacheDir->list() as $file) {
            $date = new DateTime(Str::split($file->name(), ".")[0]);
            $tp = $date->getTimestamp();
            if ($tp > $lastTimestamp) {
                $last = $file;
                $lastTimestamp = $tp;
            }
        }
        $this->currentFile = $last;
    }

    public function set($key, $value, $dur = 0)
    {
        if ($this->currentFile->isOpen()) {

            $hasSet = $this->currentFile->eachLine(function ($l, $line) use ($key, $value) {
                $arr = unserialize($l);
                if (isset($arr[$key])) {
                    $this->currentFile->replaceLine($line, serialize([$key => $value]));
                    return true;
                }
            });

            if (!$hasSet) {
                $this->currentFile->appendContent(serialize([$key => $value]));
                $this->currentFile->appendContent(PHP_EOL);
            }
        }
    }

    public function get($key)
    {

        $val = null;
        $this->currentFile->eachLine(function ($l) use ($key, &$val) {
            $arr = unserialize($l);
            if (isset($arr[$key])) {
                return $val = $arr[$key];
            }
        });

        return $val;
    }

    public function remove($key): bool
    {
        return true;
    }

    public function has($key): bool
    {
        return true;
    }

    public function clear(): ArrayList
    {
        return new ArrayList();
    }

    public function empty(): bool
    {
        return true;
    }

    public static function getDriverName(): string
    {
        return "file";
    }

    public function __destruct()
    {
        $this->currentFile->close();
    }
}
