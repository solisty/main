<?php

namespace Solisty\Database\Interfaces;

use Solisty\Database\Queryable;

interface QueryableInterface
{
    public static function select(array $columns);
    public static function where($column, $op = '=', $value);
    public static function find($id);
    public static function get();
    public static function search();
    public static function like();
    public static function only();
    public static function exists();
    public static function update();
    public static function make();
    public static function insert(array $data);
    public static function all();
    public static function delete();
}
