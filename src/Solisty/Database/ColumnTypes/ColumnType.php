<?php

namespace Solisty\Database\ColumnTypes;

abstract class ColumnType
{
	use \Solisty\Database\Traits\ColumnType;

	public function __construct(
		protected string $type = ColumnType::VARCHAR,
		protected bool  $nullable = true,
		protected ?int $length = null
	)
	{
	}

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
	public function getLength(): ?int
	{
		return $this->length;
	}

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