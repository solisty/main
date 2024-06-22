<?php

namespace Solisty\FileSystem;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Solisty\FileSystem\Interfaces\DirectoryInterface;

class Directory implements DirectoryInterface
{

    public function __construct(protected $path = '')
    {
    }

    public function open(string $dirpath)
    {
        if (is_dir($dirpath)) {
            $this->path = $dirpath;
            return true;
        } else {
            return false;
        }
    }

    public function isOpen(): bool
    {
        return isset($this->path);
    }

    public function list(): array
    {
        $openFiles = [];
        if ($this->isOpen()) {
            foreach (Directory::ls($this->path) as $path) {
                $file = new File();
                $file->open($this->path . '/' . $path);
                if ($file->isOpen()) {
                    $openFiles[] = $file;
                }
            }
            return $openFiles;
        } else {
            return [];
        }
    }

    public function empty()
    {
        if ($this->isOpen()) {
            $files = scandir($this->path);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $filePath = $this->path . DIRECTORY_SEPARATOR . $file;
                    if (is_file($filePath)) {
                        unlink($filePath);
                    } elseif (is_dir($filePath)) {
                        $this->deleteDirectory($filePath);
                    }
                }
            }
        }
    }

    protected function deleteDirectory(string $dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($filePath) ? $this->deleteDirectory($filePath) : unlink($filePath);
        }
        return rmdir($dir);
    }

    public static function ls(string $path): array
    {
        return is_dir($path) ? scandir($path) : [];
    }

    public static function mkdir(string $path)
    {
        mkdir($path, 0777, true);
    }

    public static function traverse(string $path)
    {
        $files = [];

        $items = static::ls($path);

        foreach ($items as $item) {
            if ($item !== '.' && $item !== '..') {
                $itemPath = $path . DIRECTORY_SEPARATOR . $item;
                if (is_file($itemPath)) {
                    $files[] = $itemPath;
                } elseif (is_dir($itemPath)) {
                    $files = array_merge($files, static::traverse($itemPath));
                }
            }
        }

        return $files;
    }

    public static function exists(string $path)
    {
        return file_exists($path) && is_dir($path);
    }

    public static function underNamespace($namespace): Directory
    {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
        $path = env('APP_BASE') . DIRECTORY_SEPARATOR . $path;
        return new Directory($path);
    }

    // finds a file under the open directory
    public function find($file): string
    {
        $files = static::traverse($this->path);
        foreach ($files as $f) {
            if (basename($f) == $file) {
                return $f;
            }
        }
        return '';
    }
}
