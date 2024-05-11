<?php

namespace Solisty\Main;

use Dotenv\Exception\InvalidPathException;
use Solisty\Cache\Cache;
use Solisty\FileSystem\Directory;
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

        static::$instance = $this;

        $this->initialize();
        $this->bindCommon();
        $this->bindConfigs();
        $this->bindPaths();


        Router::run();
    }

    public function handle(Request $request)
    {
        $this->injector->bind(Request::class, $request);
        $response = $this->makeResponse($request);
        $response->send();
    }

    public static function create(array $env, bool $debug)
    {
        if (!static::$instance) {
            static::$instance = new Application($env, $debug);
        }

        return static::$instance;
    }

    public function bindCommon()
    {
        $this->injector
            ->bind('app.started', $this->started)
            ->bind('app.debug', $this->debug)
            ->bind('app', $this)
            ->bind('router', Router::class)
            ->bind('env', $this->env);

        // TODO: get cache driver from config
        // TODO: set up file cache path
        $this->injector->bind(Cache::class, function () {
            return new Cache();
        })->shortcut("cache");
    }

    public function bindConfigs()
    {
        $configPath = env('APP_BASE') . '/conf';
        $this->env->add('CONFIG_PATH', $configPath);
        if (Directory::exists($configPath)) {
            $files = Directory::traverse($configPath);
            foreach ($files as $file) {
                $conf = include $file;

                if ($conf && is_array($conf)) {
                    ppd("TODO: handle configs");
                }
            }
        } else {
            throw new InvalidPathException("Connot find configuration directory");
        }
    }

    public function bindPaths()
    {
        // $this->routesPath = $this->env->get('ROUTES_PATH');
        $this->configPath = $this->env->get('CONFIG_PATH');

        $this->injector->bind('path.app', $this->appBase);
        // $this->injector->bind('path.routes', $this->routesPath);
        $this->injector->bind('path.config', $this->configPath);
    }

    public function initialize()
    {
        $this->appBase = $this->env->get('APP_BASE');
    }
}
