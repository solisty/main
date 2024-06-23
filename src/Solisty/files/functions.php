<?php

use Solisty\Dumper\Dumper;
use Solisty\Http\Session\Session;
use Solisty\List\ArrayList;
use Solisty\Main\Application;
use Solisty\Routing\Router;
use Solisty\View\View;

function view($name, $data = []): View
{
    $view = new View($name);
    return $view->with($data);
}

function pp(...$args)
{
    foreach ($args as $arg) {
        Dumper::dump($arg);
    }
}

function ppd(...$args)
{
    pp(...$args);
    die('');
}

function response()
{
}

function auth()
{
    return app('auth');
}

function session(): ?Session
{
    return app('session');
}

function listify(...$args): ArrayList
{
    return new ArrayList(array_values(...$args));
}

function app($key)
{
    if (Application::$instance) {
        return Application::$instance->retrieve($key);
    } else {
        return null;
    }
}

function base_url()
{
    $hostName = $_SERVER['HTTP_HOST'];
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';

    return $protocol . '://' . $hostName . "/";
}

function route($name, ...$params)
{
    return Router::generateURL($name, ...$params);
}

function env($key = null)
{
    if (!$key) return app('env');
    
    $val = app('env')->get($key);
    if ($val) return $val;
    return null;
}
