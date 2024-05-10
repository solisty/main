<?php

namespace Solisty\FileSystem\Interfaces;

interface FileInterface {
    public function open(string $path);

    public function isOpen(): bool;

    public function extension();

    public function isImage();

    public function isCode();

    public function content();

    public function overrideContent(string $content): bool;

    public function appendContent(string $content): bool;

    public function lines();

    public function eachLine(callable $callback): bool;
}