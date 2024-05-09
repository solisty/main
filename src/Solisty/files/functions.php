<?php

function view($name, $data = []) {}

function route($name, ...$params) {}

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

