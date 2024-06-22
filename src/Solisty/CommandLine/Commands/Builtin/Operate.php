<?php

namespace Solisty\CommandLine\Commands\Builtin;

use Exception;
use Solisty\CommandLine\Commands\Command;
use Solisty\CommandLine\Process;
use Solisty\CommandLine\Traits\CaptureOutput;
use Solisty\CommandLine\Traits\CaptureStdout;
use Solisty\Database\Database;
use Solisty\Database\Operation;

class Operate extends Command
{
    private Database $db;

    public function __construct()
    {
        $this->name = "operate";
    }

    public function run(array $argv)
    {
        $this->db = new Database();
        $this->db->connect();

        $this->runCreateOperations();
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
