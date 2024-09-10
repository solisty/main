<?php

namespace Solisty\Database;

use Solisty\Database\ColumnTypes\Integer;

// example
class ExampleModel
{
	protected ?Integer $id;


	public function schema()
	{
		$this->id = new Integer(autoIncrement: true);
	}

	public function __construct()
	{
		$this->schema();
	}

	public function __get($name)
	{
		return "hi";
	}
}

// this class will utilize the Reflection API to generate a schema for a given model
// we rely on the typed properties to determine the type of the column
// we can also use the Reflection API to determine the default value of the column
// TODO: work on a cross-datastore schema generator
class SchemaGenerator
{
	private Schema $schema;
	private ?Model $model;

	/**
	 * @param Model $model The model that this generator will handle
	 */
	public function __construct(Model $model)
	{
		$this->model = $model;
		$this->schema = new Schema();
	}

	/**
	 * Generates a Schema that represents the model
	 * @return Schema|null
	 */
	public function generate(): ?Schema
	{
		try {
			$reflection = new \ReflectionClass($this->model);
			$properties = $reflection->getProperties(\ReflectionProperty::IS_PROTECTED);

			foreach ($properties as $property) {
				$name = $property->getName();

				if (str_starts_with($name, "_")) {
					continue;
				}

				// type here is our high level representation
				$type = $property->getType();
				// default value of the column if present
				$default = $this->getModelColumnDefaultValue($name);
				// is the column auto increment
				$autoIncrement = $this->getModelColumnAutoIncrement($name);
				// is NULL allowed as a value
				$nullable = $this->getModelColumnNullable($name);

				if ($type === null) {
					continue;
				}

				$column = new SchemaColumn(
					name: $name,
					type: $type,
					autoIncrement: $autoIncrement,
					nullable: $nullable,
					default: $default
				);
				$this->schema->addColumn($column);
			}

			return $this->schema;
		} catch (\ReflectionException $e) {
			echo $e->getMessage();
			return null;
		}
	}

	public function getModel(): ?Model
	{
		return $this->model;
	}

	private function getModelColumnAutoIncrement(string $col): bool
	{
		// TODO
		return false;
	}

	private function getModelColumnNullable(string $col): bool
	{
		// TODO
		return false;
	}

	private function getModelColumnDefaultValue(string $col)
	{
		// TODO
		return null;
	}
}
