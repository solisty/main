<?php

namespace Solisty\Routing\Core;

use Exception;
use Solisty\Http\Interfaces\MiddlewareInterface;

class Route
{
    private URI $uri;
    private $handler;
    private string $method;
    private bool $isRedirect;
    private URI $redirectUri;
    private int $redirectStatus;
    private ?string $name = null;
    private array $middlewares;

    public function __construct(string $uri, $handler, string $method)
    {
        $this->uri = new URI($uri);
        $this->handler = $handler;
        $this->method = $method;
        $this->middlewares = [];
    }

    public function match(URI $uri): bool
    {
        return $this->uri->match($uri);
    }

    public function handle()
    {
        if (is_callable($this->handler)) {
            $this->preHandleMiddlewares();
            return call_user_func_array($this->handler, $this->uri->getParameters());
        }

        // TODO: create haveController method
        if (is_array($this->handler) && count($this->handler) === 2) {
            $result = app('app')->callControllerMethod(
                $this->handler[0],
                $this->handler[1],
                $this->uri->getParameters()
            );

            return $result;
        }

        throw new Exception('Invalid route handler provided.');
    }

    public function handleRedirect()
    {
        return die('unimplemented function: ' . __FUNCTION__);
    }

    public function name(string $name): Route
    {
        $this->name = $name;

        return $this;
    }

    public function where(string $segmentName, string $regex): Route
    {
        $this->uri->registerWhereOnSegment($segmentName, $regex);

        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function applyPrefix($prefix)
    {
        $this->uri = new URI($prefix . $this->uri->getUri());
    }

    public function redirect(string $route, int $status): Route
    {
        $this->isRedirect = true;
        $this->redirectUri = new URI($route);
        $this->redirectStatus = $status;

        return $this;
    }

    public function getURI()
    {
        return $this->uri;
    }

    public function getName()
    {
        return $this->name;
    }

    private function configureMiddlewares(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            $middlewareClass = app()
                ->on('app.middlewares')
                ->resolve($middleware);

            if ($middlewareClass) {
                $this->middlewares[] = new $middlewareClass;
            }
        }
    }

    public function middleware(string|array $middlewares)
    {
        if (gettype($middlewares) == 'array')
            $this->configureMiddlewares($middlewares);
        else if (gettype($middlewares) == 'string')
            $this->configureMiddlewares([$middlewares]);
    }

    private function preHandleMiddlewares()
    {
        foreach ($this->middlewares as $m) {
            $m->handle();
        }
    }

    public function postHandleMiddlewares()
    {
        foreach ($this->middlewares as $m) {
            $m->terminate();
        }
    }
}
