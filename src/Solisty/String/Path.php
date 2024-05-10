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
        return realpath($path);
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
}
