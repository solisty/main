<?php

namespace Solisty\Main;

use Dotenv\Exception\InvalidPathException;
use Solisty\Authentication\Auth;
use Solisty\Cache\Cache;
use Solisty\Database\Database;
use Solisty\FileSystem\Directory;
use Solisty\Http\Request;
use Solisty\Http\Session\Session;
use Solisty\Main\Interfaces\ApplicationInterface;
use Solisty\Routing\Router;

class Application extends Context implements ApplicationInterface
{
    private string $appBase;
    private Database $db;
    private string $configPath;
    private bool $started = false;
    public static ?Application $instance = null;

    public function __construct(public array $envo, public bool $debug, public bool $cliMode)
    {
        $this->initialize();
        $this->bindCommon();
        $this->bindConfigs();
        $this->bindPaths();

        if (!$this->cliMode) {
            Router::run();
        }
    }

    public function handle(Request $request)
    {
        // make the current request accessible to the rest of the app
        $this->injector->bind(Request::class, $request);
        // create a response
        $response = $this->makeResponse($request);
        // send it!
        $response->send();
    }

    public static function create(array $env, bool $debug = false, bool $cliMode = false)
    {
        if (!static::$instance) {
            static::$instance = new Application($env, $debug, $cliMode);
        }

        return static::$instance;
    }

    public function bindCommon()
    {
        // order is important
        $this->injector
            ->bind('app.started', $this->started)
            ->bind('app.debug', $this->debug)
            ->bind('env', $this->env)
            ->bind('app', $this);

        if (!$this->cliMode) {
            $this->db = new Database();
            $this->db->connect();

            $this->injector
                ->bind('session', new Session)
                ->bind('auth', new Auth)
                ->bind('db', $this->db);
        }

        $this->injector
            ->bind('router', Router::class);

        // TODO: get cache driver from config
        // TODO: set up file cache path
        $this->injector->bind(Cache::class, function () {
            return (new Cache())->driver();
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
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();

        $this->setEnv($this->envo);
        $this->injector = new DependencyInjector();
        static::$instance = $this;
        $this->appBase = $this->env->get('APP_BASE');
    }

    public function bind($key, $value)
    {
        $this->injector->bind($key, $value);
    }

    public function setControllerValue($value)
    {
        $this->controllerValue = $value;
    }
}
