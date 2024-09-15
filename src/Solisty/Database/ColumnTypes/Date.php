<?php

namespace Solisty\Database\ColumnTypes;

use Solisty\Database\ColumnTypes\ColumnType;
use Solisty\Database\Interfaces\ColumnType as ColumnTypeInterface;

class Date extends ColumnType implements ColumnTypeInterface
{

	/**
	 * @param bool $nullable
	 * @param string|null $default
	 */
	public function __construct(
		public bool $nullable = false,
		public ?string $default = null
	) {
		parent::__construct(self::INT);
	}

	/**
	 * @inheritDoc
	 */
	public function getType(): string
	{
		return self::DATE;
	}

	/**
	 * @inheritDoc
	 */
	public function isNullable(): bool
	{
		return $this->nullable;
	}

	/**
	 * @inheritDoc
	 */
	public function getDefault(): ?string
	{
		return $this->default;
	}
}