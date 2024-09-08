<?php
namespace Solisty\Database\ColumnTypes;

abstract class ColumnType
{
    /**
     * Get the type of the column
     *
     * @return string
     */
    abstract public function getType(): string;

    /**
     * Get the length of the column
     *
     * @return int
     */
    abstract public function getLength(): int;

    /**
     * Check if the column is nullable
     *
     * @return bool
     */
    abstract public function isNullable(): bool;

    /**
     * Get the default value of the column
     *
     * @return string|null
     */
    abstract public function getDefault(): ?string;
}