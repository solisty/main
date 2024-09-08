<?php

namespace Solisty\Http;

use Solisty\String\Str;

class Request
{
    public const HTTP_METHOD_GET = 'GET';
    public const HTTP_METHOD_POST = 'POST';
    public const HTTP_METHOD_PUT = 'PUT';
    public const HTTP_METHOD_PATCH = 'PATCH';
    public const HTTP_METHOD_DELETE = 'DELETE';
    public const HTTP_METHOD_PURGE = 'PURGE';
    public const HTTP_METHOD_OPTIONS = 'OPTIONS';
    public const HTTP_METHOD_TRACE = 'TRACE';
    public const HTTP_METHOD_CONNECT = 'CONNECT';

    private HeaderList $header;
    private string $method;
    private string $content;
    private string $encoding;
    private array $accepts;
    private string $locale;

    public function __construct()
    {
        $this->header = new HeaderList();
        $this->parseGlobals();
    }

    public static function makeGet(): Request
    {
        $request = new self();
        $request->method = self::HTTP_METHOD_GET;
        return $request;
    }

    public static function makePost(): Request
    {
        $request = new self();
        $request->method = self::HTTP_METHOD_POST;
        return $request;
    }

    public static function makeEmpty(): Request
    {
        return new self();
    }

    public function parseFiles(): void
    {
        // Implementation for parsing files goes here
    }

    public function make(): void
    {
        // Implementation for making a request goes here
    }

    public function parseHeader(): void
    {
        foreach ($_SERVER as $key => $value) {
            if (Str::startWith($key, 'HTTP_')) {
                $headerName = Str::replace($key, 'HTTP_', '');
                $headerName = Str::split($headerName, '_');
                $headerName = Str::join(Str::split(Str::capitalize(Str::join($headerName, ' ')), ' '), '-');
                $this->header->add($headerName, $value);
            }
        }
    }

    public function parseGlobals(): void
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->content = file_get_contents('php://input') ?? '';
        $this->encoding = $_SERVER['HTTP_CONTENT_ENCODING'] ?? '';
        $this->accepts = explode(',', $_SERVER['HTTP_ACCEPT'] ?? '');
        $this->locale = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        $this->parseHeader();
    }

    public function getFullUrl(): string
    {
        $scheme = $this->isSecure() ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        return $scheme . $host . $uri;
    }

    public function parseCookies(): array
    {
        return $_COOKIE;
    }

    public function getAuthType(): ?string
    {
        return $_SERVER['AUTH_TYPE'] ?? null;
    }

    public function getAuthToken(): ?string
    {
        $authHeader = $this->header->get('Authorization');
        if ($authHeader) {
            return trim(str_replace('Bearer', '', $authHeader));
        }
        return null;
    }

    public function getUserAgent(): ?string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }

    public function isProxy(): bool
    {
        return !empty($_SERVER['HTTP_VIA']);
    }

    public function isSecure(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    }

    public function haveContent(): bool
    {
        return !empty($this->content);
    }

    public function mightHaveContent(): bool
    {
        return in_array($this->method, [self::HTTP_METHOD_POST, self::HTTP_METHOD_PUT, self::HTTP_METHOD_PATCH, self::HTTP_METHOD_DELETE]);
    }

    public function getRequestMethod(): string
    {
        return $this->method;
    }

    public function needsJson(): bool
    {
        return in_array('application/json', $this->accepts);
    }

    public function params(): array
    {
        return $_REQUEST;
    }

    public function hasFiles(): bool
    {
        return !empty($_FILES);
    }

    public function files(): array
    {
        return $_FILES;
    }

    public function isSuspicious(): bool
    {

        return false;
    }

    public function isBot(): bool
    {
        $userAgent = $this->getUserAgent();
        if ($userAgent) {
            return preg_match('/bot|crawl|slurp|spider/i', $userAgent) > 0;
        }
        return false;
    }

    public function isCrawler(): bool
    {
        return $this->isBot();
    }

    public function saveAllFiles(): void {}

    public function json(): array
    {
        return json_decode($this->content, true);
    }

    public function validate(array $rules): bool
    {

        return true;
    }

    public function user() {}

    public function validator() {}

    public function abort(int $statusCode = 400): void
    {
        http_response_code($statusCode);
        die();
    }

    public function isAsset(): bool
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        return preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/i', $uri) > 0;
    }

    public function isJson(): bool
    {
        return Str::endsWith($_SERVER['HTTP_ACCEPT'], 'application/json');
    }
}
