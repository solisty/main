<?php

namespace Solisty\Database;

use Exception;
use LengthException;
use Solisty\List\ArrayList;
use Solisty\String\Str;
use Solisty\FileSystem\Directory;
use Solisty\String\Path;

class QueryBuilder
{

    public string $query;
    private ?string $table;
    private $columns;
    private array $arrayColumns;
    private array $whereClauses = [];
    private array $values = [];

    public function __construct()
    {
        // by default set to '*' and later can be changed by setColumns
        $this->setColumns();
    }

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
        $this->buildSelectSQLQuery();
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
        $c = count($this->whereClauses);
        if ($c > 0) {
            $where = "WHERE ";
            foreach ($this->whereClauses as $i => $clause) {
                // $formatted = app('db')->getDriver()->typeFormat($clause[2]);
                $where .= "{$clause[0]}{$clause[1]}?";
                $where .= ($i != $c - 1) ? ' AND ' : '';
            }
            return $where;
        }

        return '';
    }

    public function setOrderBy(string $order, string $direction = 'ASC'): QueryBuilder
    {
        return $this;
    }

    public function where(string $column, $op, $value = null): QueryBuilder
    {
        return $this->addWhereClause($column, $op, $value);
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

        $this->values[] = $value;

        return $this;
    }

    public function setLimit(int $limit): QueryBuilder
    {
        return $this;
    }

    public function get(): ArrayList
    {
        // find the model class
        // a hard guess!
        // TODO: get the calling class instead
        $modelName = Str::capitalize(Str::singularize($this->table));
        $class = Directory::underNamespace('App\\Models')->find($modelName . '.php');
        $model = new (Path::toNamespaced($class));

        $db = app('db');
        $this->buildSelectSQLQuery();
        [$query, $values] = $this->getRawQuery();
        $db->query($query, $values);

        $result = $db->getDriver()->fetchAll();
        $list = listify([]);

        foreach ($result as $row) {
            $list->add($model::fromResult($row));
        }

        return $list;
    }

    public function getRawQuery(): array
    {
        return [
            $this->query,
            $this->values,
        ];
    }

    private function buildSelectSQLQuery(): string
    {
        $this->query = "SELECT {$this->getColumnNamesAsString()} FROM {$this->table} {$this->getWhereClausesAsString()}";
        return $this->query;
    }

    public function getColumnNamesAsString()
    {
        return implode(',', $this->arrayColumns);
    }
}
