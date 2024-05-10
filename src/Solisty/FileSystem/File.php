<?php

namespace Solisty\FileSystem;

use Solisty\FileSystem\Interfaces\FileInterface;

class File implements FileInterface
{
    protected $path;
    protected $extension;
    protected $mimetype;
    protected $isOpen;

    public function open(string $path)
    {
        if (file_exists($path) && is_file($path)) {
            $this->path = $path;
            $this->extension = pathinfo($path, PATHINFO_EXTENSION);
            $this->mimetype = mime_content_type($path);
            $this->isOpen = true;
            return true;
        } else {
            return false;
        }
    }

    public function isOpen(): bool
    {
        return $this->isOpen;
    }

    public function extension(): ?string
    {
        return $this->extension;
    }

    public function isImage(): bool
    {
        return strpos($this->mimetype, 'image/') === 0;
    }

    public function isCode(): bool
    {
        // TODO: to be expanded
        $codeExtensions = ['php', 'js', 'html', 'css', 'java', 'py', 'rb'];
        return in_array($this->extension, $codeExtensions);
    }

    public function content(): ?string
    {
        if ($this->isOpen) {
            return file_get_contents($this->path);
        } else {
            return null;
        }
    }

    public function overrideContent(string $content): bool
    {
        if ($this->isOpen) {
            return file_put_contents($this->path, $content) !== false;
        } else {
            return false;
        }
    }

    public function appendContent(string $content): bool
    {
        if ($this->isOpen) {
            return file_put_contents($this->path, $content, FILE_APPEND) !== false;
        } else {
            return false;
        }
    }

    public function lines(): ?array
    {
        if ($this->isOpen) {
            return file($this->path, FILE_IGNORE_NEW_LINES);
        } else {
            return null;
        }
    }

    public function eachLine(callable $callback): bool
    {
        if ($this->isOpen) {
            $handle = fopen($this->path, 'r');
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $result = $callback($line);
                    if ($result === false) {
                        fclose($handle);
                        return false;
                    }
                }
                fclose($handle);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
