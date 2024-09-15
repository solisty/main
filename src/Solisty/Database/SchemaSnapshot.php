<?php

namespace Solisty\Database;

use Solisty\Database\ColumnTypes\Date;
use Solisty\Database\ColumnTypes\Json;
use Solisty\Database\ColumnTypes\ShortString;

class SchemaSnapshot extends Model
{
	protected \Solisty\Database\ColumnTypes\Integer $id;
	protected ShortString $modelName;
	protected Json $columnDefinitions;
	protected Json $indexDefinitions;
	protected ?Date $createdAt;

	public function __construct() {
		$this->id = new \Solisty\Database\ColumnTypes\Integer();
		$this->modelName = new ShortString();
		$this->columnDefinitions = new Json();
		$this->indexDefinitions = new Json();
		$this->createdAt = new Date();
	}
}