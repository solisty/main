<?php

use Solisty\Main\Application;
use Solisty\Routing\Router;
use Solisty\View\View;

function view($name, $data = []) {
    $view = new View();
    $view->show($name, $data);
}

function pp(...$args) {
    echo '<pre>';
    foreach($args as $arg) {
        var_dump($arg);
    }
    echo '</pre>';
}

function ppd(...$args) {
    pp(...$args);
    die('');
}

function response() {}

function session(string $key, $value) {}

function listify(...$args) {}

function app($key) {
    if (Application::$instance) {
        return Application::$instance->retrieve($key);
    }
}

function getBaseUrl()
{
    $hostName = $_SERVER['HTTP_HOST'];
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';

    return $protocol . '://' . $hostName . "/";
}

function route($name, ...$params)
{
    return Router::generateURL($name, ...$params);
}
