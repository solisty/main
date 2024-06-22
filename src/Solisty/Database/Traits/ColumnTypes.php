<?php

namespace Solisty\Database\Traits;

trait ColumnTypes
{
    public function integer(): Column
    {
        $this->type = "INTEGER";
        return $this;
    }

    public function string($length = 255)
    {
        $this->type = "VARCHAR({$length})";
        
    }

    public function date($column)
    {
    }

    public function timestamps($column)
    {
    }

    public function softDelete()
    {
    }

    // we don't want to copy laravel, huh!
    public function foreignId($column)
    {
    }
}
