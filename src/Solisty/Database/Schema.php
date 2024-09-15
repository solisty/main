<?php

namespace Solisty\Database;

class Schema
{
	/**
	 * @var SchemaColumn[]
	 */
	private array $columns = [];

	public function __construct(private string $table)
	{
	}

	/**
	 * @param SchemaColumn $column
	 * @return void
	 */
	public function addColumn(SchemaColumn $column): void
	{
		$this->columns[] = $column;
	}

	public function getColumns(): array
	{
		return $this->columns;
	}

	/**
	 * Generates a CREATE TABLE sql statement
	 * @return string
	 */
	public function getCreateSql(): string
	{
		return "CREATE TABLE {$this->getTableName()} ({$this->getColumnsSql()})";
	}

	private function getTableName(): string
	{
		return $this->table;
	}

	private function getColumnsSql(): string
	{
		$sql = '';
		foreach ($this->columns as $i => $column) {
			$sql .= $column->getSql();

			if ($i < count($this->columns) - 1) {
				$sql .= ', ';
			}
		}

		return $sql;
	}
}