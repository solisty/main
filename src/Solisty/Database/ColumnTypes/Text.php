<?php

namespace Solisty\Database\ColumnTypes;

use Solisty\Database\Interfaces\ColumnType as ColumnTypeInterface;

class Text extends ColumnType implements ColumnTypeInterface
{
    /**
     * Text constructor
     *
     * @param string $name
     * @param int $length
     * @param bool $nullable
     * @param string|null $default
     */
    public function __construct(
        public int $length = 255,
        public bool $nullable = false,
        public ?string $default = null
    ) {}

    /**
     * Get the type of the column
     *
     * @return string
     */
    public function getType(): string
    {
        return 'TEXT';
    }

    /**
     * Get the length of the column
     *
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Check if the column is nullable
     *
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * Get the default value of the column
     *
     * @return string|null
     */
    public function getDefault(): ?string
    {
        return $this->default;
    }
}
