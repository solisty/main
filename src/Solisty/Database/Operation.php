<?php

namespace Solisty\Database;

use Exception;
use Solisty\List\ArrayList;

class Operation
{
    private $title;
    private $description;
    private $updatedAt;
    private $createdAt;
    private $deletedAt;
    private $relatedTable;
    private static array $CollectedTables;
    private static bool $collect = false;

    public static function run(string $name, callable $callback)
    {
        $table = new Table($name);
        $callback($table);

        // ...

        if (static::$collect) {
            static::$CollectedTables[] = $table;
        }
    }

    public static function reverse(string $for, callable $callback)
    {
    }

    public static function collect()
    {
        static::$collect = true;
        static::$CollectedTables = [];
    }

    public static function getClean()
    {
        $tables = [...static::$CollectedTables];
        static::$CollectedTables = [];
        return $tables;
    }
}
