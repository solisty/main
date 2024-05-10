<?php

namespace Solisty\List;

use Solisty\List\Interfaces\ArrayListInterface;
use Solisty\List\Interfaces\ListInterface;

class ArrayList implements ListInterface, ArrayListInterface
{
    protected int $size;

    public function __construct(protected array $data = [])
    {
        $this->data = $data;
        $this->size = count($data);
    }

    public function add($item)
    {
        $this->data[] = $item;
        $this->size++;
    }

    public function remove($item)
    {
        $index = array_search($item, $this->data);
        if ($index !== false) {
            array_splice($this->data, $index, 1);
            $this->size--;
        }
    }

    public function removeAt($index)
    {
        if (isset($this->data[$index])) {
            array_splice($this->data, $index, 1);
            $this->size--;
        }
    }

    public function update($index, $item)
    {
        if (isset($this->data[$index])) {
            $this->data[$index] = $item;
        }
    }

    public function clear()
    {
        $this->data = [];
        $this->size = 0;
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

    public function reduce($callback, $initial = null)
    {
        return array_reduce($this->data, $callback, $initial);
    }

    public function shift()
    {
        return array_shift($this->data);
    }

    public function last()
    {
        return end($this->data);
    }

    public function first() {
        return reset($this->data);
    }

    public function toArray()
    {
        return $this->data;
    }

    public function at($index)
    {
        return $this->data[$index];
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

    public function fill($value)
    {
        $this->data = array_fill(0, $this->size, $value);
    }

    public function size()
    {
        return $this->size;
    }

    public function grow()
    {
        $this->size *= 2;
    }
}
