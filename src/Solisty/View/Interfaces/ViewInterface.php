<?php

namespace Solisty\View\Interfaces;

use Solisty\View\View;

interface ViewInterface {

    public function show($view = '', $data = []): ?View;

    public function with(array $data = []): View; 

    public function getTemplatePath(): string;

    public function getCompiledPath(): string;

    public static function fromPath(string $viewPath): View;

    public static function fromHTML(string $htmlContent): View;
}