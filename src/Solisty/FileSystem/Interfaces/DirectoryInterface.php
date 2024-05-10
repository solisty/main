<?php

namespace Solisty\FileSystem\Interfaces;

interface DirectoryInterface
{
    public function open(string $dirpath);
    public function isOpen(): bool;
    public function list(): array;
    public function empty();
}
