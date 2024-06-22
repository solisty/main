<?php

namespace Solisty\Database;

use Solisty\Database\Traits\HasColumns;

class Table
{
    use HasColumns;

    private Operation $createOperation;

    public function __construct(public string $name)
    {
    }

    public static function new(string $name, array $columns): Table
    {
        ppd($columns);
    }

    public static function update(string $name, array $columns)
    {
        ppd($columns);
    }

    public function op(int $op)
    {
        pp('op', $op);
    }

    public function getCreateQuery()
    {
    }
}
