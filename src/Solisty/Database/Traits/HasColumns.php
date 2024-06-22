<?php

namespace Solisty\Database\Traits;

use Exception;

trait HasColumns
{
    public array $columns;

    public function __call($name, $arguments): Column
    {
        $col = null;
        switch ($name) {
            case "integer":
                $col = $this->createIntegerColumn($arguments[0]);
                break;
            case "string":
                $col = $this->createStringColumn($arguments[0], $arguments[1] ?? 255);
                break;
            case "text":
                $col = $this->createTextColumn($arguments[0], $arguments[1] ?? 512);
                break;
            case "timestamps":
                $col = $this->createTimestampsColumns();
                break;
            default:
                throw new Exception("Unknown column type");
        }

        $this->columns[] = $col;
        return $col;
    }

    public function createIdColumn($name)
    {
        $col = new Column($name);
        return $col->integer()->autoIncrement()->primaryKey();
    }

    public function createIntegerColumn($name)
    {
        $col = new Column($name);
        return $col->integer($name);
    }

    public function createStringColumn($name, $length = 255)
    {

        return new Column($name);
    }

    public function createTextColumn($name, $length = 512)
    {

        return new Column($name);
    }

    public function createTimestampsColumns()
    {
        return new Column("created_at");
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getColumnsAsSQL(): string
    {
        $sql = [];

        foreach ($this->columns as $col) {
            $sql[] = $col->assembleLine();
        }

        return implode(',', $sql);
    }
}
