<?php

namespace Solisty\Database;

use Exception;
use LengthException;

class QueryBuilder
{

    public string $query;
    private ?string $table;
    private $columns;
    private array $arrayColumns;
    private array $whereClauses = [];
    private array $values = [];

    public static function mysql_getInsertOneQuery(array $one, string $table): string
    {
        $q = "INSERT INTO $table (";
        if (isset($one['keys']) && isset($one['values'])) {
            if (count($one['keys']) != count($one['values']))
                throw new LengthException("Insertion into mysql columns' count does not equal values' count");
            $q .= implode(',', $one['keys']) . ')';
            foreach ($one['values'] as $value) {
                $values[] = \Solisty\Database\DBs\MySQL::typeFormat($value);
            }
            $q .= " VALUES (" . implode(',', $values) . ")";
        }

        return $q;
    }

    public function setup(): bool
    {
        return !empty($this->table);
    }

    public function setTable(string $tableName): QueryBuilder
    {
        $this->table = $tableName;

        return $this;
    }

    public function setColumns(array|string|null $columns = ['*']): QueryBuilder
    {
        $this->columns = is_array($columns) ? implode(',', $columns) : $columns;
        $this->arrayColumns = is_array($columns) ? $columns : [$columns];
        return $this;
    }

    public function select()
    {
        $select = "SELECT {$this->columns} FROM {$this->table} {$this->getWhereClausesAsString()}";
        $this->query = $select;

        return $this;
    }

    public function insert($values)
    {
        foreach ($values as $key => $value) {
            if (!in_array($key, $this->arrayColumns)) {
                throw new Exception("Attempting to insert a property '{$key}' that doesn't exist in model");
            }
        }

        $placeholders = implode(',', array_map(fn ($v) => '?', $values));
        $this->values = array_values($values);

        $this->query = "INSERT INTO {$this->table}({$this->columns}) VALUES ($placeholders)";
        return $this;
    }

    public function getTableInfo(): string
    {
        $db_name = env('DATABASE_NAME');
        return "DESCRIBE $db_name.{$this->table}";
    }

    public function getWhereClausesAsString(): string
    {
        $where = "";
        $c = count($this->whereClauses);
        if ($c > 0) {
            $where = "WHERE ";
            foreach ($this->whereClauses as $i => $clause) {
                // $formatted = app('db')->getDriver()->typeFormat($clause[2]);
                $where .= "{$clause[0]}{$clause[1]}?";
                $where .= ($i != $c - 1) ? ' AND ' : '';
                $this->values[] = $clause[2];
            }
        }

        return $where;
    }

    public function setOrderBy(string $order, string $direction = 'ASC'): QueryBuilder
    {
        return $this;
    }

    public function addWhereClause(string $column, $op, $value = null): QueryBuilder
    {
        if ($value == null) {
            $value = $op;
            $op = "=";
        }

        $this->whereClauses[] = [
            $column,
            $op,
            $value
        ];

        return $this;
    }

    public function setLimit(int $limit): QueryBuilder
    {
        return $this;
    }

    public function get(): array
    {
        $this->buildSQLQuery();
        return [
            $this->query,
            $this->values,
        ];
    }

    private function buildSQLQuery(): string
    {
        return "SELECT * FROM {$this->table}";
    }
}
