<?php

namespace Solisty\String;

class Path
{
    public static function base($path)
    {
        return basename($path);
    }

    public static function join($paths)
    {
        return implode(DIRECTORY_SEPARATOR, $paths);
    }

    public static function toAbs($path)
    {
        // returns the absolute path of a relative path from env('APP_BASE')
        if (self::isAbs($path)) {
            return $path;
        } else {
            return self::join([env('APP_BASE'), $path]);
        }
    }

    public static function toRelative($path)
    {
        // returns the relative path of an absolute path from env('APP_BASE')
        if (self::isAbs($path)) {
            return str_replace(env('APP_BASE'), '', $path);
        } else {
            return $path;
        }
    }

    public static function isRelative($path)
    {
        return !self::isAbs($path);
    }

    public static function posix($path)
    {
        return str_replace('\\', '/', $path);
    }

    public static function windows($path)
    {
        return str_replace('/', '\\', $path);
    }

    public static function extension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public static function isAbs($path)
    {
        if (strpos($path, DIRECTORY_SEPARATOR) === 0) {
            return true; // Absolute path on Unix-like systems
        } elseif (preg_match('/^[a-zA-Z]:\\\\/', $path)) {
            return true; // Absolute path on Windows
        } else {
            return false; // Relative path
        }
    }

    public static function split($path)
    {
        return explode(DIRECTORY_SEPARATOR, $path);
    }

    public static function toNamespaced($path)
    {
        // handle windows paths and relative paths
        // example: C:\xampp\htdocs\app\src\Main.php
        // output: App\Src\Main
        $path = self::toRelative($path);
        $path = self::trim($path, '/');
        $path = self::trim($path, '\\');
        $path = self::trim($path, '.php');
        $path = self::split($path);
        $path = array_map(function ($part) {
            return Str::capitalize($part);
        }, $path);
        return Str::join($path, '\\');
    }

    public static function trim($path, $char)
    {
        return trim($path, $char);
    }
}
