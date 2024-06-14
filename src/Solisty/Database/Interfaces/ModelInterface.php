<?php

namespace Solisty\Database\Interfaces;

use Solisty\Database\Model;

interface ModelInterface {
    public static function create(array $attributes): Model;
    public static function creating(callable $callback): void;
    public static function saving(callable $callback): void;
    public static function removing(callable $callback): void;
    public static function updating(callable $callback): void;
}