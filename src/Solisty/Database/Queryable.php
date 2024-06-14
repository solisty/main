<?php

namespace Solisty\Database;

use Exception;
use Solisty\Database\Interfaces\QueryableInterface;
use Solisty\String\Str;

class Queryable implements QueryableInterface
{
    protected static string $table = '';
    private static QueryBuilder $query;

    public static function select(array $columns)
    {
        return new Queryable();
    }

    public static function where()
    {
        return new Queryable();
    }

    public static function init()
    {
        static::$query = new QueryBuilder();
    }

    public static function find($id)
    {
        $db = app('db');
        if (!self::$query->setup()) {
            static::setTable();
            self::$query->addWhereClause('id', $id);
            self::$query->setColumns('*')->select();
        }

        [$query, $values] = self::$query->get();
        $db->query($query, $values);
        return static::class::fromResult($db->getDriver()->fetchOne());
    }

    public static function setTable()
    {
        // get table protected property
        $table = static::$table;
        if (empty($table)) {
            // attempt to guess table name if none worked
            $modelPath = Str::split(static::class, '\\');
            $table = Str::pluralize(Str::lowercase(end($modelPath)));
        }
        self::$query->setTable($table);
    }

    public static function get()
    {
        return new Queryable();
    }

    public static function search()
    {
        return new Queryable();
    }

    public static function like()
    {
        return new Queryable();
    }

    public static function only()
    {
        return new Queryable();
    }

    public static function exists()
    {
        return new Queryable();
    }

    public static function update()
    {
        return new Queryable();
    }

    public static function make()
    {
        return new Queryable();
    }

    public static function insert(array $data)
    {
        $db = app('db');

        if (!self::$query->setup()) {
            static::setTable();
            $db->query(self::$query->getTableInfo(), []);
            $columns = $db->getDriver()->get();
            $model = static::class;
            $object = new $model;

            // map non-provided columns to null
            foreach ($columns as $column) {
                if (!isset($data[$column['Field']])) {
                    // TODO: control for AUTO_INCREMENT and default values
                    if ($column['Field'] !== 'id') {
                        $data[$column['Field']] = null;
                    }
                }
            }

            foreach ($data as $key => $value) {
                $object->assign($key, $value);
            }
            
            self::$query->setColumns(array_keys($data));
            self::$query->insert($data);
            [$query, $values] = self::$query->get();
            $db->query($query, $values);
        }
    }

    public static function all()
    {
        return new Queryable();
    }

    public static function delete()
    {
        return new Queryable();
    }
}
