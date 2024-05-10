<?php

namespace Solisty\Main;

use Solisty\Http\Request;
use Solisty\Main\Interfaces\ApplicationInterface;
use Solisty\Routing\Router;

class Application extends Context implements ApplicationInterface
{
    private string $appBase;
    private string $routesPath;
    private string $configPath;
    private bool $started = false;
    private bool $debug = false;
    public static ?Application $instance = null;

    public function __construct(array $env, bool $debug)
    {
        $this->setEnv($env);
        $this->debug = $debug;
        $this->injector = new DependencyInjector();

        $this->initialize();

        $this->bindCommon();
        $this->bindConfigs();
        $this->bindPaths();

        static::$instance = $this;
    }

    public function handle(Request $request)
    {
        $response = $this->makeResponse($request);
        $response->send();
    }

    public static function create(array $env, bool $debug)
    {
        if (!static::$instance) {
            static::$instance = new Application($env, $debug);
        }

        Router::run();

        return static::$instance;
    }

    public function bindCommon()
    {
        $this->injector->bind('app.started', $this->started);
        $this->injector->bind('app.debug', $this->debug);
    }

    public function bindConfigs()
    {
    }

    public function bindPaths()
    {
        $this->injector->bind('path.app', $this->appBase);
        $this->injector->bind('path.routes', $this->routesPath);
        $this->injector->bind('path.config', $this->configPath);
    }

    public function initialize() {
        $this->appBase = $this->env->get('APP_BASE');
        $this->routesPath = $this->env->get('ROUTES_PATH');
        $this->configPath = $this->env->get('CONFIG_PATH');
    }
}
