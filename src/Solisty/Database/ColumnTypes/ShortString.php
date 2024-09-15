<?php

namespace Solisty\Database\ColumnTypes;

use Solisty\Database\Interfaces\ColumnType as ColumnTypeInterface;

class ShortString extends ColumnType implements ColumnTypeInterface
{
	use \Solisty\Database\Traits\ColumnType;

	/**
	 * ShortString constructor
	 *
	 * @param bool $nullable
	 * @param string|null $default
	 */
    public function __construct(
        public bool $nullable = false,
        public ?string $default = null,
    ) {
	    parent::__construct(nullable: $nullable, length: 255);
    }

    /**
     * Get the type of the column
     *
     * @return string
     */
    public function getType(): string
    {
        return 'VARCHAR';
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
