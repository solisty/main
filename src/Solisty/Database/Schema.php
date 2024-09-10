<?php
namespace Solisty\Database;

class Schema
{
    private array $columns = [];

    public function addColumn(SchemaColumn $column)
    {
        $this->columns[] = $column;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}