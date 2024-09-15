<?php

namespace Solisty\Database;

use Solisty\Database\ColumnTypes\ColumnType;

class SchemaColumn
{
	private string $name;
	private string $type;
	private bool $autoIncrement;
	private bool $nullable;
	private mixed $default;
	private ?int $length;

	public function __construct(string $name, string $type, bool $autoIncrement = false, bool $nullable = false, mixed $default = null, ?int $length = null)
	{
		$this->name = $name;
		$this->type = $this->mapType($type);
		$this->autoIncrement = $autoIncrement;
		$this->nullable = $nullable;
		$this->default = $default;
		$this->length = $length;
	}

	// all getters

	public function getName(): string
	{
		return $this->name;
	}

	public function getType(): string
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
		$map = [
			\Solisty\Database\ColumnTypes\Integer::class => 'INTEGER',
			\Solisty\Database\ColumnTypes\ShortString::class => 'VARCHAR',
		];

		if (isset($map[$type])) {
			return $map[$type];
		}

		return "VARCHAR";
	}

	public function getSql(): string
	{
		$autoIncrement = $this->isAutoIncrement() ? ' AUTO_INCREMENT PRIMARY KEY' : '';
		$nullable = $this->isNullable() ? ' NULL' : ' NOT NULL';
		$default = $this->getDefault() !== null ? ' DEFAULT ' . $this->getDefault() : '';
		$typeLength = $this->length ? $this->getType() . "($this->length)" : $this->getType();
		return "{$this->getName()} {$typeLength}{$autoIncrement}{$nullable}{$default}";
	}

}