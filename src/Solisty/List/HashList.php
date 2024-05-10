<?php

namespace Solisty\List;

use Solisty\List\Interfaces\HashListInterface;
use Solisty\List\Interfaces\ListInterface;
class HashList implements ListInterface, HashListInterface {
    protected array $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function add($key, $value)
    {
        $index = $this->hash($key);
        $this->data[$index] = ['key' => $key, 'value' => $value];
    }

    public function removeAt($key)
    {
        $index = $this->hash($key);
        unset($this->data[$index]);
    }

    public function update($key, $value)
    {
        $index = $this->hash($key);
        if (isset($this->data[$index])) {
            $this->data[$index]['value'] = $value;
        }
    }

    public function remove($key)
    {
        $index = $this->hash($key);
        unset($this->data[$index]);
    }

    public function hash($key)
    {
        // DJB2 Hash Function
        $hash = 5381;
        $len = strlen($key);
        for ($i = 0; $i < $len; $i++) {
            $hash = (($hash << 5) + $hash) + ord($key[$i]);
        }
        return $hash;
    }

    // Implementing ListInterface methods...

    public function clear()
    {
        $this->data = [];
    }

    public function each($callback)
    {
        foreach ($this->data as $item) {
            $callback($item);
        }
    }

    public function map($callback)
    {
        return array_map($callback, $this->data);
    }

    public function filter($callback)
    {
        return array_filter($this->data, $callback);
    }

    public function reduce($callback)
    {
        return array_reduce($this->data, $callback);
    }

    public function shift()
    {
        $item = reset($this->data);
        if ($item !== false) {
            unset($this->data[key($this->data)]);
        }
        return $item;
    }

    public function last()
    {
        return end($this->data);
    }

    public function toArray()
    {
        return $this->data;
    }

    public function __toString()
    {
        return implode(', ', $this->data);
    }

    public function empty()
    {
        return empty($this->data);
    }

    public function slice($start, $end)
    {
        return array_slice($this->data, $start, $end - $start);
    }

    public function first() {
        return reset($this->data);
    }

    public function get($key) {
        $index = $this->hash($key);
        if (isset($this->data[$index])) {
            return $this->data[$index]['value'];
        }
    }

    public function fill($value)
    {
        foreach ($this->data as &$item) {
            $item['value'] = $value;
        }
    }

    public function size()
    {
        return count($this->data);
    }
}