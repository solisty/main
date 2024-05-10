<?php

require 'vendor/autoload.php';

use Solisty\Http\Http;
use Solisty\Main\Application;

$app = Application::create([
    'APP_NAME' => "solisty",
    "APP_BASE" => dirname(__DIR__),
    "ROUTES_PATH" => "./routes.php",
    "CONFIG_PATH" => "./"
], false);

$request = Http::make();

$app->handle($request);
