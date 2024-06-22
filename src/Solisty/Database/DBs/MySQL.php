<?php

namespace Solisty\Database\DBs;

use Exception;
use PDO;
use PDOStatement;
use Solisty\Database\Interfaces\DBInterface;

class MySQL implements DBInterface
{
    private bool $connected = false;
    private bool $isPrepared = false;
    private PDOStatement $prepared;
    private array $values;
    private $result;

    public function __construct(private ?PDO $pdo = null)
    {
    }

    public function tryConnect(): void
    {
        try {
            $this->connect();
            $this->connected = true;
        } catch (Exception $err) {
            throw $err;
        }
    }

    private function connect()
    {
        $connectionInfo = $this->constructConnectionInfo();
        $this->pdo = new PDO(
            $connectionInfo['dsn'],
            $connectionInfo['user'],
            $connectionInfo['pass']
        );

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function constructConnectionInfo(): array
    {
        $dbname = env('DATABASE_NAME');
        $dbhost = env('DATABASE_HOST');
        $dbuser = env('DATABASE_USER');
        $dbpass = env('DATABASE_PASS');

        return [
            'dsn' => "mysql:host=$dbhost;dbname=$dbname",
            'user' => $dbuser,
            'pass' => $dbpass
        ];
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    public function runQuery(string $query, array $options = []): string
    {
        return "";
    }

    public static function typeFormat($value): string
    {
        switch (gettype($value)) {
            case "integer":
                return $value;
            case "string":
                return "'$value'";
            default:
                return $value;
        }
    }

    public function queryUsing($query, $bindValues): bool
    {
        // if (strlen($query) > 30) {
        //     ppd($query, $bindValues);
        // }
        $this->prepared = $this->pdo->prepare($query);
        return $this->prepared->execute($bindValues);;
    }

    public function query($query): bool
    {
        $this->prepared = $this->pdo->prepare($query);
        return $this->prepared->execute();
    }

    public function fetchOne(): array
    {
        return $this->prepared->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll()
    {
        return $this->prepared->fetchAll(PDO::FETCH_ASSOC);
    }

    public function prepare(): void
    {
    }

    public function havePrepared(): bool
    {
        return false;
    }

    public function containsValues(): bool
    {
        return false;
    }

    public static function escape(string $value): string
    {
        // TODO: logic
        return $value;
    }
    public static function sanitize(string $value): string
    {
        // TODO: logic
        return $value;
    }
}
