<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Core;

use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Application class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Application extends HttpKernel
{
    /**
     * @var array
     */
    protected $modules = [];

    /**
     * @var Container
     */
    protected $container;

    public function __construct()
    {
        $this->container = new Container();

        $this->initConfigs();

        $this->container->init($this);

        $this->initRoutes();
        $this->initModules();
        $this->initEnvironment();
        $this->initTimezone();
        $this->initUnicode();

        $this->container['kernel'] = $this;

        parent::__construct($this->container['dispatcher'], $this->container['resolver']);
    }

    /**
     * @return array
     */
    protected function getConfigsFromFiles()
    {
        $app_config = [];
        if (file_exists(CONFIG_PATH . 'config.php')) {
            $app_config = include_once APPLICATION_PATH . 'configs/config.php';
        }
        $config = include_once dirname(__DIR__) . '/Core/configs/config.php';
        $config = array_replace_recursive($config, $app_config);

        if (!isset($config['modules'])) {
            return $config;
        }
        foreach ($config['modules'] as $module) {
            $module_config = include_once(dirname(__DIR__) . '/Modules/' . ucfirst($module) . '/configs/config.php');
            $config = array_replace_recursive($module_config, $config);
        }

        return $config;
    }

    protected function initConfigs()
    {
        $config = $this->getConfigsFromFiles();
        foreach ($config as $option => $value) {
            $this->container[$option] = $value;
        }
    }

    protected function initRoutes()
    {
        foreach ($this->container['routes'] as $route_name => $route_value) {
            $defaults = isset($route_value['defaults'])
                ? $route_value['defaults']
                : [];
            $requirements = isset($route_value['requirements'])
                ? $route_value['requirements']
                : [];
            $options = isset($route_value['options'])
                ? $route_value['options']
                : [];

            /** @var RouteCollection $collection */
            $collection = $this->container['routes-collection'];
            $collection->add($route_name, new Route($route_value['path'], $defaults, $requirements, $options));
        }
    }

    protected function initModules()
    {
        if (isset($this->container['modules'])) {
            foreach ($this->container['modules'] as $module) {
                $module_provider_name = 'Nameless\\Modules\\' . $module . '\\ModuleProvider';
                $module_provider = new $module_provider_name($this->container);

                if (!$module_provider instanceof ModuleProvider) {
                    throw new \RuntimeException($module_provider_name . ' must be instance of ModuleProvider');
                }

                $this->modules[$module] = $module_provider;
                $module_provider->register($this->container);
            }
        }
    }

    /**
     * @return ModuleProvider[]
     */
    public function getModuleProviders()
    {
        return $this->modules;
    }

    /**
     * @param string $module
     *
     * @return ModuleProvider
     */
    public function getModuleProvider($module)
    {
        return $this->modules[$module];
    }

    protected function initTimezone()
    {
        if (isset($this->container['timezone'])) {
            date_default_timezone_set($this->container['timezone']);
        }
    }

    /**
     * @throws \RuntimeException
     */
    protected function initUnicode()
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->container['dispatcher'];
        $dispatcher->addSubscriber(new ResponseListener('UTF-8'));

        if (!extension_loaded('mbstring')) {
            throw new \RuntimeException('mbstring extension must be enabled!');
        }
        mb_internal_encoding('UTF-8');
    }

    protected function initEnvironment()
    {
        error_reporting(-1);

        if ($this->container['environment'] === 'debug') {
            ExceptionHandler::register();
        } else {
            if ($this->container['environment'] === 'production') {
                error_reporting(E_ALL ^ (E_STRICT | E_NOTICE | E_DEPRECATED));
                ini_set('display_errors', 0);
            }
            $listener = new ExceptionListener($this->container['error_controller']);
            $this->container['dispatcher']->addSubscriber($listener);
        };

        $handler = ErrorHandler::register(null, true);
        $handler->setDefaultLogger($this->container['logger.logger']);
    }

    /**
     * @param BaseRequest $request
     * @param integer $type
     * @param boolean $catch
     *
     * @return Response
     */
    public function handle(BaseRequest $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $this->container['request'] = $request;
        return parent::handle($request, $type, $catch);
    }

    /**
     * @param string $route
     * @param array $attributes
     * @param array $query
     *
     * @return Response
     */
    public function forward($route, array $attributes = [], array $query = [])
    {
        $defaults = $this->container['routes-collection']->get($route)->getDefaults();
        $attributes['_controller'] = $defaults['_controller'];
        $attributes['_route']      = $route;

        $subRequest = $this->container['request']->duplicate($query, null, $attributes);
        return $this->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    /**
     * @param Request $request
     */
    public function run(Request $request = null)
    {
        if (is_null($request)) {
            $request = Request::createFromGlobals();
        }
        $response = $this->handle($request);
        $response->send();
        $this->terminate($request, $response);
    }

    /**
     * @param BaseRequest $request
     * @param Response $response
     */
    public function terminate(BaseRequest $request, Response $response)
    {
        $this->dispatcher->dispatch(KernelEvents::TERMINATE, new PostResponseEvent($this, $request, $response));
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
