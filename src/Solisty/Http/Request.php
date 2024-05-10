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


    private string $method;
    private HeaderList $header;


    public function __construct()
    {
        $this->header = new HeaderList();
        $this->parseGlobals();
    }

    public static function makeGet()
    {
    }

    public static function makePost()
    {
    }

    public static function makeEmpty(): Request
    {
        return new Request();
    }

    public function parseFiles()
    {
    }

    public function make()
    {
    }

    public function parseHeader(): void
    {
        foreach ($_SERVER as $key => $value) {
            if (Str::startWith($key, 'HTTP_')) {
                $this->header->add(
                    Str::join(Str::split(Str::capitalize(Str::join(Str::split(Str::replace($key, 'HTTP_', ''), '_'), ' ')), ' '), '-'),
                    $value
                );
            }
        }
    }

    public function parseGlobals(): void
    {
        $this->parseHeader();
    }

    public function getFullUrl() {}

    public function parseCookies() {}

    public function getAuthType() {}

    public function getAuthToken() {}

    public function getUserAgent() {}

    public function isProxy() {}

    public function isSecure() {}

    public function haveContent() {}

    public function mightHaveContent() {}

    public function getRequestMethod() {}

    public function needsJson() {}

    public function params() {}

    public function hasFiles() {}

    public function files() {}

    public function isSuspicious() {}

    public function isBot() {}

    public function isCrawler() {}

    public function saveAllFiles() {}

    public function json() {}

    public function validate() {}

    public function user() {}

    public function validator() {}

    public function abort() {}

    public function isAsset() {}
}
