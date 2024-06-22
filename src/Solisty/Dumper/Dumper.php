<?php

namespace Solisty\Dumper;

use Solisty\View\View;
use Solisty\String\Str;

class Dumper
{
    public static function dump($arg)
    {
        ob_start();
        var_dump($arg);
        $dump = ob_get_clean();
        if (ob_get_level() > 0) ob_flush();
        // TODO: use html View::fromHTML($viewString)

        if (app('app')->cliMode) {
            return $dump;
        }

        $dump = Str::replace($dump, '\\', '\\\\');
        view(__DIR__ . '/view.php', compact('dump'))->show();
    }
}
