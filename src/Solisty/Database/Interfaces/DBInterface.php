<?php

namespace Solisty\Database\Interfaces;

interface DBInterface
{
    public function tryConnect(): void;
    public function isConnected(): bool;
    public function constructConnectionInfo(): array;
    public function queryUsing($query, $bindValues): bool;
    public function prepare(): void;
    public function havePrepared(): bool;
    public function containsValues(): bool;
    public function fetchOne(): array;
    public function get();

    // public function getInsertManyQuery(array $many);
    public static function typeFormat($value): string;

    public static function escape(string $value): string;
    public static function sanitize(string $value): string;
}
