<?php

namespace Solisty\Http;

use HeaderList;

class Request {
    private HeaderList $header;
    

    public function __construct()
    {
        
    }

    public static function makeGet() {}

    public static function makePost() {}

    public static function makeEmpty(): Request {
        return new Request();
    }

    public function parseFiles() {}

    public function make() {}

}