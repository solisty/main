<?php

namespace Solisty\View;

use Solisty\View\Compilers\FlameCompiler;

abstract class Template
{
    protected string $templatePath = "";
    protected FlameCompiler $compiler;

    public function __construct()
    {
        $this->compiler = new FlameCompiler();
        $this->compiler->registerBuiltinDirectives();
    }

    public function compile(): void
    {
        if (!file_exists($this->templatePath)) {
            die("Error: view file not found");
        }
        ob_start();
        include_once $this->templatePath;
        $content = ob_get_clean();

        $this->compiler->compileString($content);
    }

    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }
}
