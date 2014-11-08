<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2014. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Core;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\Debug;

define('NAMELESS_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

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
    private $modules = [];

    /**
     * @var boolean
     */
    private $booted = false;

    /**
     * @var Container
     */
    private $container;

    public function __construct()
    {
        $this->container = new Container();
        $this->container->init();

        $this->configurationInit();
        $this->routsInit();
        $this->modulesInit();
        $this->environmentInit();

        $this->container['kernel'] = $this;

        parent::__construct($this->container['dispatcher'], $this->container['resolver']);
    }

    //TODO: rework for nested arrays (recursive)
    private function configurationInit()
    {
        $app_config = [];
        if (file_exists(CONFIG_PATH . 'configuration.php')) {
            $app_config = include_once CONFIG_PATH . 'configuration.php';
        }

        $default_config = include_once NAMELESS_PATH . 'Core' . DS . 'configs' . DS . 'configuration.php';
        $config = array_merge($default_config, $app_config);

        foreach ($config as $config_option => $config_value) {
            if (is_array($config_value)) {
                foreach ($config_value as $module_option => $module_value) {
                    $full_module_option = $config_option . '.' . $module_option;
                    $this->container[$full_module_option] = $module_value;
                }
            }
            $this->container[$config_option] = $config_value;
        }
    }

    private function routsInit()
    {
        foreach ($this->container['routes'] as $route_name => $route_value) {
            $defaults = isset($route_value['defaults']) ? $route_value['defaults'] : [];
            $requirements = isset($route_value['requirements']) ? $route_value['requirements'] : [];
            $options = isset($route_value['options']) ? $route_value['options'] : [];

            $this->container['routes-collection']->add($route_name, new Route($route_value['pattern'], $defaults, $requirements, $options));
        }
    }

    private function modulesInit()
    {
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

    /**
     * @throws \RuntimeException
     */
    private function environmentInit()
    {
        date_default_timezone_set($this->container['timezone']);

        $this->container['dispatcher']->addSubscriber(new ResponseListener('UTF-8'));

        if ($this->container['environment'] === 'debug') {
            Debug::enable();
            ErrorHandler::setLogger($this->container['logger.logger'], 'deprecation');
            ErrorHandler::setLogger($this->container['logger.logger'], 'emergency');
        } else {
            if ($this->container['environment'] === 'test') {
                error_reporting(-1);
                ini_set('display_errors', 1);
            } else {
                error_reporting(E_ALL ^ (E_STRICT | E_NOTICE | E_DEPRECATED));
                ini_set('display_errors', 0);
            }

            $listener = new ExceptionListener($this->container['error_controller']);
            $this->container['dispatcher']->addSubscriber($listener);
        };

        if (!extension_loaded('mbstring')) {
            throw new \RuntimeException('mbstring extension must be enabled!');
        }
        mb_internal_encoding('UTF-8');
    }

    public function boot()
    {
        if (!$this->booted) {
            foreach ($this->modules as $module) {
                $module->boot($this);
            }
            $this->booted = true;
        }
        return $this;
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
        if (!$this->booted) {
            $this->boot();
        }
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