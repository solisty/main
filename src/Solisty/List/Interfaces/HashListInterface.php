<?php

namespace Solisty\List\Interfaces;

interface HashListInterface {
    public function add($key, $value);
    public function removeAt($key);
    public function update($key, $value);
    public function remove($key);
    public function get($key);
    public function hash($key);
}