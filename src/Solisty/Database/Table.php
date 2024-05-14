<?php

namespace Solisty\Database;

class Table {
    public static function new(string $name, array $columns): Table {
        ppd($columns);

    }

    public static function update(string $name, array $columns) {
        ppd($columns);
    }

    public function op(int $op) {
        pp('op', $op);
    }
}