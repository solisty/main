<?php

namespace Solisty\List\Interfaces;

interface ArrayListInterface {
    public function add($item);
    public function removeAt($index);
    public function update($index, $item);
    public function remove($item);
    public function grow();
    public function at($index);
}