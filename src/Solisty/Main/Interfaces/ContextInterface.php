<?php

namespace Solisty\Main\Interfaces;

use Solisty\Http\Request;
use Solisty\Http\Response;

interface ContextInterface {
    public function makeResponse(Request $request): Response;
}