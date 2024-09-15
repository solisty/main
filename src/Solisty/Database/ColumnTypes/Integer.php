<?php

namespace Solisty\Database\ColumnTypes;

use Solisty\Database\Interfaces\ColumnType as ColumnTypeInterface;

class Integer extends ColumnType implements ColumnTypeInterface
{
	use \Solisty\Database\Traits\ColumnType;

	/**
	 * Integer constructor
	 *
	 * @param ?int $length
	 * @param bool $unsigned
	 * @param bool $autoIncrement
	 * @param bool $nullable
	 * @param string|null $default
	 */
	public function __construct(
		public ?int     $length = null,
		public bool    $unsigned = false,
		public bool    $autoIncrement = false,
		public bool    $nullable = false,
		public ?string $default = null
	)
	{
		parent::__construct(self::INT);
	}

	/**
	 * Get the type of the column
	 *
	 * @return string
	 */
	public function getType(): string
	{
		return self::INT;
	}

	/**
	 * Check if the column is unsigned
	 *
	 * @return bool
	 */
	public function isUnsigned(): bool
	{
		return $this->unsigned;
	}

	/**
	 * Check if the column is auto increment
	 *
	 * @return bool
	 */
	public function isAutoIncrement(): bool
	{
		return $this->autoIncrement;
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
