<?php
namespace Solisty\Database\Traits;

trait ColumnType {
    public const INT = 'INTEGER';
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
	 * @return bool
	 */
    public function isNumeric(): bool
    {
        return in_array($this->getType(), [self::INT, self::FLOAT, self::DOUBLE, self::DECIMAL]);
    }

    /**
     * Check if the column is a string
     *
     * @return bool
     */
    public function isString(): bool
    {
        return in_array($this->getType(), [self::VARCHAR, self::TEXT]);
    }

    /**
     * Check if the column is a temporal type
     *
     * @return bool
     */
    public function isTemporal(): bool
    {
        return in_array($this->getType(), [self::DATE, self::DATETIME, self::TIMESTAMP, self::TIME]);
    }

    /**
     * Check if the column is a spatial type
     *
     * @return bool
     */
    public function isSpatial(): bool
    {
        return in_array($this->getType(), [
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
    public function isJson(): bool
    {
        return $this->getType() === self::JSON;
    }
}