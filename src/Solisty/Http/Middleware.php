<?php

namespace Solisty\Http;

use Solisty\Http\Interfaces\MiddlewareInterface;

class Middleware implements MiddlewareInterface {
    public function handle(Request $request) { }

    public function terminate() { }
}