<?php

namespace Solisty\View;

use Solisty\View\Interfaces\ViewInterface;

class View extends Template implements ViewInterface
{
    private string $compiledPath = "";
    private string $htmlContent = "";
    private array $data = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function show($view = '', $data = []): ?View
    {
        if (!empty($view)) {
            $this->setTemplatePath($view);
        }

        $this->compile($this->getTemplatePath());
        $this->prepareInstance();

        extract($this->data);
        extract($data);

        if (empty($this->compiledPath)) {
            echo $this->getHtmlContent();
            return null;
        }

        include $this->compiledPath;
        return $this;
    }

    public function prepare()
    {
        return View::fromPath($this->compiler->getCachedFilePath());
    }

    public function compileView(): void
    {
        $this->compile($this->templatePath);
        $this->prepareInstance();
    }

    public function prepareInstance()
    {
        $this->setHtmlContent($this->compiler->getCompiledContent())
            ->setCompiledPath($this->compiler->getCachedFilePath());
    }

    public function setCompiledPath(string $path)
    {
        $this->compiledPath = $path;
    }

    public function getCompiledPath(): string
    {
        return "";
    }

    public static function fromPath(string $viewPath): View
    {
        return (new View())->setTemplatePath($viewPath);
        // return View::fromHTML("");
    }

    public function setTemplatePath(string $path): View
    {
        $this->templatePath = $path;
        return $this;
    }

    public static function fromHTML(string $htmlContent): View
    {
        $view = new View();
        $view->htmlContent = $htmlContent;
        return $view;
    }

    public function getHtmlContent()
    {
        if (!empty($this->htmlContent)) {
            return $this->htmlContent;
        }

        return "<code>Empty View</code>";
    }

    public function setHtmlContent(string $htmlContent)
    {
        $this->htmlContent = $htmlContent;
        return $this;
    }

    public function with(array $data = []): View
    {
        $this->data = $data;
        return $this;
    }
}
