<?php

namespace Solisty\List\Interfaces;

interface ListInterface
{
    public function clear();
    public function each($callback);
    public function map($callback);
    public function filter($callback);
    public function reduce($callback);
    public function shift();
    public function first();
    public function last();
    public function toArray();
    public function __toString();
    public function empty();
    public function slice($start, $end);
    public function fill($value);
    public function size();
}
