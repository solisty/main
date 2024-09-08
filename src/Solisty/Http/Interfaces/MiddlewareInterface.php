<?php

namespace Solisty\Http\Interfaces;

use Solisty\Http\Request;

// a Base class for all middlewares
interface MiddlewareInterface
{
    public function handle(Request $request);

    public function terminate();
}
