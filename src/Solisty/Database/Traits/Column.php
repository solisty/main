<?php

namespace Solisty\Database\Traits;

trait Column
{
    use ColumnTypes;

    private string $sqlDefinition;
    private array $modifiers;
    private bool $isNull = false;
    private bool $isPrimaryKey;

    public function __construct(private string $name, private string $type = "VARCHAR(255)")
    {
        $this->modifiers = [];
    }

    public function autoIncrement(): Column
    {
        $this->modifiers[] = "AUTO_INCREMENT";
        return $this;
    }

    public function primaryKey(): Column
    {
        $this->modifiers[] = "PRIMARY KEY";

        return $this;
    }

    public function nullable(): Column
    {
        $this->isNull = true;
        
        return $this;
    }

    public function assembleLine(): string
    {
        if (!$this->isNull) {
            $this->modifiers[] = "NOT NULL";
        }

        $modifiers = implode(' ', $this->modifiers);
        return "{$this->name} {$this->type} $modifiers";
    }
}
