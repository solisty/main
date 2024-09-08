<?php

namespace Solisty\Main;

use Dotenv\Exception\InvalidPathException;
use Solisty\Authentication\Auth;
use Solisty\Cache\Cache;
use Solisty\Database\Database;
use Solisty\FileSystem\Directory;
use Solisty\FileSystem\File;
use Solisty\Http\Request;
use Solisty\Http\Session\Session;
use Solisty\Main\Interfaces\ApplicationInterface;
use Solisty\Routing\Router;

class Application extends Context implements ApplicationInterface
{
    private string $appBase;
    private Database $db;
    private string $configPath;
    private $startTime;
    private bool $started = false;
    public static ?Application $instance = null;

    public function __construct(public array $envo, public bool $debug, public bool $cliMode)
    {
        parent::__construct();
        $this->startTime = microtime(true);
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
        $this->bind(Request::class, $request);
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
        $this->bind('app.started', $this->started)
            ->bind('app.debug', $this->debug)
            ->bind('env', $this->env)
            ->bind('app', $this);

        if (!$this->cliMode) {
            $this->db = new Database();
            $this->db->connect();

            $this->bind('session', new Session)
                ->bind('db', $this->db)
                ->bind('auth', new Auth);
        }

        $this
            ->bind('router', Router::class);

        // TODO: get cache driver from config
        // TODO: set up file cache path
        $this->bind(Cache::class, function () {
            return (new Cache())->driver();
        })->shortcut("cache");
    }

    public function bindConfigs()
    {
        // this routine should be cached
        $configPath = env('APP_BASE') . '/conf';
        $this->env->add('CONFIG_PATH', $configPath);
        if (Directory::exists($configPath)) {
            $files = Directory::traverse($configPath);
            foreach ($files as $file) {
                $conf = include $file;

                if ($conf && is_array($conf)) {
                    foreach ($conf as $key => $value) {
                        if (is_array($value)) {
                            $this->on('app.' . $key)->bindArray($value);
                        } else {
                            $this->bind('app.' . $key, $value);
                        }
                    }
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

        $this->bind('path.app', $this->appBase);
        // $this->injector->bind('path.routes', $this->routesPath);
        $this->bind('path.config', $this->configPath);
    }

    public function initialize()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();

        $this->setEnv($this->envo);
        static::$instance = $this;
        $this->appBase = $this->env->get('APP_BASE');
    }

    public function bind($key, $value): DependencyInjector
    {
        $this->bindGlobalScope($key, $value);

        return $this->globalScope;
    }

    public function setControllerValue($value)
    {
        $this->controllerValue = $value;
    }

    public function __destruct()
    {
        // ...
    }
}
