<?php
namespace Solisty\Database;

use Solisty\Database\ColumnTypes\ColumnType;

class SchemaColumn {
    private string $name;
    private string $type;
    private bool $autoIncrement;
    private bool $nullable;
    private mixed $default;

    public function __construct(string $name, string $type, bool $autoIncrement = false, bool $nullable = false, mixed $default = null)
    {
        $this->name = $name;
        $this->type = $this->mapType($type);
    }

    // all getters

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): ColumnType
    {
        return $this->type;
    }

    public function isAutoIncrement(): bool
    {
        return $this->autoIncrement;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }

    // map our type classes with the database specific types
    public function mapType(string $type): string
    {
		printf("mapping type: %s\n", $type);

        $map = [
            \Solisty\Database\ColumnTypes\Integer::class => 'INTEGER',
        ];

        return $map[$type];
    }

}