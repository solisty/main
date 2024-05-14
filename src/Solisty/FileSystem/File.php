<?php

namespace Solisty\FileSystem;

use Solisty\FileSystem\Interfaces\FileInterface;

class File implements FileInterface
{
    protected $path;
    protected $extension;
    protected $mimetype;
    protected $isOpen;
    protected $handle;

    public function open(string $path)
    {
        if (file_exists($path) && is_file($path)) {
            $this->path = $path;
            $this->extension = pathinfo($path, PATHINFO_EXTENSION);
            $this->mimetype = mime_content_type($path);
            $this->isOpen = true;
            $this->handle = fopen($path, 'r+');
            return $this->isOpen;
        } else {
            $this->isOpen = false;
            return false;
        }
    }

    public function replace(string $content): bool
    {
        if ($this->isOpen) {
            ftruncate($this->handle, 0);
            rewind($this->handle);
            return fwrite($this->handle, $content) !== false;
        } else {
            return false;
        }
    }

    public function replaceLine(int $lineNumber, string $newLine): bool
    {
        if ($this->isOpen) {
            $lines = [];
            while (($line = fgets($this->handle)) !== false) {
                $lines[] = $line;
            }

            if (isset($lines[$lineNumber - 1])) {
                $lines[$lineNumber - 1] = $newLine . PHP_EOL;
                rewind($this->handle);
                ftruncate($this->handle, 0);
                foreach ($lines as $line) {
                    fwrite($this->handle, $line);
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function name()
    {
        $ext = $this->extension();
        return pathinfo($this->path, PATHINFO_FILENAME) . ($ext ? "." . $ext : "");
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
        $codeExtensions = ['php', 'js', 'html', 'css', 'java', 'py', 'rb'];
        return in_array($this->extension, $codeExtensions);
    }

    public function content(): ?string
    {
        if ($this->isOpen) {
            return stream_get_contents($this->handle);
        } else {
            return null;
        }
    }

    public function overrideContent(string $content): bool
    {
        if ($this->isOpen) {
            rewind($this->handle);
            return fwrite($this->handle, $content) !== false;
        } else {
            return false;
        }
    }

    public function appendContent(string $content): bool
    {
        if ($this->isOpen) {
            fseek($this->handle, 0, SEEK_END);
            return fwrite($this->handle, $content) !== false;
        } else {
            return false;
        }
    }

    public function lines(): ?array
    {
        if ($this->isOpen) {
            $lines = [];
            rewind($this->handle);
            while (($line = fgets($this->handle)) !== false) {
                $lines[] = $line;
            }
            return $lines;
        } else {
            return null;
        }
    }

    public function eachLine(callable $callback): bool
    {
        if ($this->isOpen) {
            rewind($this->handle);
            $lineNo = 1;
            while (($line = fgets($this->handle)) !== false) {
                $result = $callback($line, $lineNo);
                if ($result) {
                    return true;
                }
                $lineNo++;
            }
            return false;
        } else {
            return false;
        }
    }

    public function close(): void
    {
        if ($this->isOpen) {
            fclose($this->handle);
            $this->isOpen = false;
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}
