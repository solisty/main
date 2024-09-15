<?php

namespace Solisty\Database;

use ReflectionException;
use Solisty\Database\ColumnTypes\Integer;
use Solisty\String\Str;

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
		$table = get_class($model);
		$table = substr($table, strrpos($table, '\\') + 1);
		$this->schema = new Schema(Str::lowercase(Str::pluralize($table)));
	}

	/**
	 * Generates a Schema representing the model provided in the constructor
	 * @return Schema|null
	 * @throws ReflectionException
	 */
	public function generate(): ?Schema
	{

		$reflection = new \ReflectionClass($this->model);
		$properties = $reflection->getProperties(\ReflectionProperty::IS_PROTECTED);

		foreach ($properties as $property) {
			$name = $property->getName();

			if (str_starts_with($name, "_")) {
				continue;
			}

			// TODO: check for for types (i.e db compatible)

			// type here is our high level representation
			$type = $property->getType();
			// default value of the column if present
			$default = $this->model->{$name}->default ?? $property->getDefaultValue();
			// is the column auto increment
			$autoIncrement = $this->model->{$name}->isNumeric() ? $this->model->{$name}->autoIncrement : false;
			// is NULL allowed as a value
			$nullable = $property->getType()->allowsNull();
			// the length
			$length = $this->model->{$name}->getLength();
			// TODO: add more attributes

			if ($type === null) {
				continue;
			}

			$column = new SchemaColumn(
				name: $name,
				type: $type,
				autoIncrement: $autoIncrement,
				nullable: $nullable,
				default: $default,
				length: $length
			);
			$this->schema->addColumn($column);
		}

		return $this->schema;

	}

	public function getModel(): ?Model
	{
		return $this->model;
	}
}
