<?php

namespace Solisty\Http;

class Http {
    
    public static function make(): Request {
        return new Request;
    }
}