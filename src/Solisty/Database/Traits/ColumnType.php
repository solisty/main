<?php
namespace Solisty\Database\Traits;

trait ColumnType {
    public const INT = 'INT';
    public const VARCHAR = 'VARCHAR';
    public const TEXT = 'TEXT';
    public const DATE = 'DATE';
    public const DATETIME = 'DATETIME';
    public const TIMESTAMP = 'TIMESTAMP';
    public const TIME = 'TIME';
    public const FLOAT = 'FLOAT';
    public const DOUBLE = 'DOUBLE';
    public const DECIMAL = 'DECIMAL';
    public const BOOLEAN = 'BOOLEAN';
    public const ENUM = 'ENUM';
    public const SET = 'SET';
    public const JSON = 'JSON';
    public const BLOB = 'BLOB';
    public const GEOMETRY = 'GEOMETRY';
    public const POINT = 'POINT';
    public const LINESTRING = 'LINESTRING';
    public const POLYGON = 'POLYGON';
    public const MULTIPOINT = 'MULTIPOINT';
    public const MULTILINESTRING = 'MULTILINESTRING';
    public const MULTIPOLYGON = 'MULTIPOLYGON';
    public const GEOMETRYCOLLECTION = 'GEOMETRYCOLLECTION';

    /**
     * ColumnType constructor
     *
     * @param string $name
     * @param int $length
     * @param bool $unsigned
     * @param bool $autoIncrement
     * @param bool $nullable
     * @param string|null $default
     */
    public static function isNumeric(string $type): bool
    {
        return in_array($type, [self::INT, self::FLOAT, self::DOUBLE, self::DECIMAL]);
    }

    /**
     * Check if the column is a string
     *
     * @return bool
     */
    public static function isString(string $type): bool
    {
        return in_array($type, [self::VARCHAR, self::TEXT]);
    }

    /**
     * Check if the column is a temporal type
     *
     * @return bool
     */
    public static function isTemporal(string $type): bool
    {
        return in_array($type, [self::DATE, self::DATETIME, self::TIMESTAMP, self::TIME]);
    }

    /**
     * Check if the column is a spatial type
     *
     * @return bool
     */
    public static function isSpatial(string $type): bool
    {
        return in_array($type, [
            self::GEOMETRY,
            self::POINT,
            self::LINESTRING,
            self::POLYGON,
            self::MULTIPOINT,
            self::MULTILINESTRING,
            self::MULTIPOLYGON,
            self::GEOMETRYCOLLECTION
        ]);
    }

    /**
     * Check if the column is a JSON type
     *
     * @return bool
     */
    public static function isJson(string $type): bool
    {
        return $type === self::JSON;
    }
}