<?php

namespace Solisty\Database;

use Exception;
use Solisty\Database\Interfaces\DBInterface;
use Solisty\String\Str;

class Database
{
    private DBInterface $driver;
    private bool $isConnected = false;

    public function __construct()
    {
        $driverName = env('DATABASE');
        if ($driverName) {
            $this->driver = new ($this->driversMap()[$driverName]);
        } else {
            // default to mysql
            $this->driver = new ($this->driversMap()['mysql']);
        }

        // init queryables
        Queryable::init();
    }

    public function connect()
    {
        if (!$this->isConnected()) {
            try {
                $this->driver->tryConnect();
                $this->isConnected = $this->driver->isConnected();
            } catch (Exception $err) {
                $this->reportError($err);
            }
        }
    }

    public function initDriver()
    {
    }

    public function driverExists()
    {
    }

    public function reportError($err)
    {
        echo "<h1>Error connecting to database</h1>";
        ppd($err);
    }

    public function isConnected()
    {
        return $this->isConnected;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function driversMap()
    {
        return [
            'mysql' => \Solisty\Database\DBs\MySQL::class
        ];
    }

    public function query($query, $values)
    {
        return $this->driver->queryUsing($query, $values);
    }
}
