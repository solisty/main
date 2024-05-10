<?php

namespace Solisty\Http;

use Solisty\List\HashList;

class HeaderList extends HashList
{
    /*
        Split a header with multiple values:
        example:
            "Chromium";v="124", "Google Chrome";v="124", "Not-A.Brand";v="99"
    */
    public function split()
    {
    }
}
