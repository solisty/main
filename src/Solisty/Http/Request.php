<?php

namespace Solisty\Http;


use Solisty\String\Str;

class Request
{
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
}
