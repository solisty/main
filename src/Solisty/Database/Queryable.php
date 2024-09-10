<?php

namespace Solisty\Database;

use Exception;
use Solisty\Database\Interfaces\QueryableInterface;
use Solisty\String\Str;

class Queryable implements QueryableInterface
{
    protected static string $_table = '';
    private static QueryBuilder $queryBuilder;

    public static function select(array $columns)
    {
        return new Queryable();
    }

    public static function where($column, $op = '=', $value = null): QueryBuilder
    {
        if (!self::$queryBuilder->setup()) {
            static::setTable();
            self::$queryBuilder->addWhereClause($column, '=', $op);
        }

        return self::$queryBuilder;
    }

    public static function init()
    {
        static::$queryBuilder = new QueryBuilder();
    }

    public static function find($id)
    {
        $db = app('db');
        if (!self::$queryBuilder->setup()) {
            static::setTable();
        }

        self::$queryBuilder->addWhereClause('id', $id);
        self::$queryBuilder->setColumns('*')->select();

        [$query, $values] = self::$queryBuilder->getRawQuery();
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
        self::$queryBuilder->setTable($table);
    }

    public static function get()
    {
        $db = app('db');
        [$query, $values] = self::$queryBuilder->getRawQuery();
        $db->query($query, $values);
        // self::$queryBuilder->reset();
        return static::class::fromResult($db->getDriver()->fetchOne());
    }

    // get the first n results from the query
    // if the $num > 1 return a list otherwise return a single object
    public static function first($num = 1) {
        $db = app('db');
        [$query, $values] = self::$queryBuilder->getRawQuery();
        $db->query($query, $values);
        $result = $db->getDriver()->fetchAll();
        $resu = [];
        foreach ($result as $row) {
            $resu[] = static::class::fromResult($row);
        }
        return $num > 1 ? listify($resu) : $resu[0];
    }

    // get the latest result from the query
    public static function latest($num = 1) {

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

        if (!self::$queryBuilder->setup()) {
            static::setTable();
            $db->query(self::$queryBuilder->getTableInfo(), []);
            $columns = $db->getDriver()->fetchAll();
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

            self::$queryBuilder->setColumns(array_keys($data));
            self::$queryBuilder->insert($data);
            [$query, $values] = self::$queryBuilder->getRawQuery();
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
