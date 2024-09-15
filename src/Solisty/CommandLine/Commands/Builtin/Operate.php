<?php

namespace Solisty\CommandLine\Commands\Builtin;

use Couchbase\User;
use Exception;
use Solisty\CommandLine\Commands\Command;
use Solisty\Database\Database;
use Solisty\Database\Operation;
use Solisty\Database\SchemaGenerator;
use Solisty\Database\SchemaSnapshot;
use Solisty\FileSystem\Directory;
use Solisty\FileSystem\File;
use Solisty\String\Str;

class Operate extends Command
{
	private Database $db;

	public function __construct()
	{
		$this->name = "operate";
	}

	public function run(array $argv)
	{
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$this->db = new Database();
		$this->db->connect();

		// loop through models directory recursively
		$models = Directory::underNamespace('App\Models')->getAbsoluteFilesPath();
		$schemas = [];

		foreach ($models as $model) {
			try {
				$className = Str::split(basename($model), '.')[0];
				$namespace = Str::replace($model, $className . '.php', '');
				$namespaced = "\\App\\Models\\" . $namespace . $className;
				$object = new $namespaced();
				$schemas[] = (new SchemaGenerator($object))->generate();
			} catch (Exception $e) {
				// TODO: error reporting for CLI
				echo $e->getMessage();
				echo $e->getTraceAsString();
			}
		}

		foreach ($schemas as $schema) {
			$sql = $schema->getCreateSql();
			echo $sql . "\n";
			var_dump($this->db->query($sql));
		}
	}


	public function onStart(array $env)
	{
		//
	}

	// called when cmd is done running
	public function onExit($statusCode)
	{
	}

	public function runCreateOperations()
	{
		$filePath = env('APP_BASE') . "/db/create.php";
		if (file_exists($filePath)) {
			try {
				Operation::collect();
				include $filePath;
				$createOperations = Operation::getClean();

				foreach ($createOperations as $table) {
					$this->db->createTable($table);
				}
			} catch (Exception $ex) {
				echo $ex;
			}
		}
	}

	public function runUpdateOperations()
	{
	}
}
