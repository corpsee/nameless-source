<?php

/*
 * This file is part of the Nameless framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Nameless\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\EventListener\LocaleListener;
use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Route;
use Nameless\Modules\Auto\User;

class Kernel extends HttpKernel implements HttpKernelInterface
{
	/**
	 * @var array
	 */
	private $modules = array();

	/**
	 * @var boolean
	 */
	private $booted = FALSE;

	/**
	 * @var integer
	 */
	private $start_time;

	/**
	 * @var Container
	 */
	private $container;

	/**
	 *
	 */
	public function __construct()
	{
		// container/kernel
		$this->container           = new \Pimple();
		$this->container['kernel'] = $this;
		$this->container['logger'] = NULL;

		$this->configurationInit();
		$this->routerInit();
		$this->modulesInit();
		$this->sessionInit();
		$this->dispatcherInit();
		$this->environmentInit();

		parent::__construct($this->container['dispatcher'], $this->container['resolver']);
	}

	private function configurationInit ()
	{
		$app_config = array();
		if (file_exists(CONFIG_PATH . 'configuration.php'))
		{
			$app_config = include_once(CONFIG_PATH . 'configuration.php');
		}

		$default_config = include_once(ROOT_PATH . 'Nameless' . DS . 'Configs' . DS . 'configuration.php');
		$config         = array_merge($default_config, $app_config);

		foreach ($config as $config_option => $config_value)
		{
			$this->container[$config_option] = $config_value;
		}
	}

	private function routerInit ()
	{
		$routes = array();
		if (file_exists(CONFIG_PATH . 'routes.php'))
		{
			$routes = include_once(CONFIG_PATH . 'routes.php');
		}

		$this->container['routes'] = $this->container->share(function ()
		{
			return new RouteCollection();
		});

		foreach ($routes as $route_name => $route_value)
		{
			$defaults     = isset($route['defaults']) ? $route['defaults'] : array();
			$requirements = isset($route['requirements']) ? $route['requirements'] : array();
			$options      = isset($route['options']) ? $route['options'] : array();

			$this->container['routes']->add($route_name, new Route($route['pattern'], $defaults, $requirements, $options));
		}

		$this->container['matcher'] = $this->container->share(function ($c)
		{
			$context  = new RequestContext($c['http_port'], $c['https_port']);
			return new UrlMatcher($c['routes'], $context);
		});

		$this->container['resolver'] = $this->container->share(function ($c)
		{
			return new ControllerResolver($c, $c['logger']);
		});
	}

	private function modulesInit ()
	{
		foreach ($this->container['modules'] as $module)
		{
			$module_provider_name = 'Nameless\\Modules\\' . $module . '\\ModuleProvider';
			$module_provider      = new $module_provider_name($this->container);

			if (!$module_provider instanceof ModuleProviderInterface)
			{
				throw new \RuntimeException($module_provider_name . ' must be instance of ModuleProviderInterface');
			}

			$this->modules[$module] = $module_provider;
			$module_provider->register($this->container);
		}
	}

	private function sessionInit ()
	{
		$this->container['session_options'] = array();
		$this->container['session_default_locale'] = $this->container['locale'];
		$this->container['session_path'] = '';

		$this->container['session_handler'] = $this->container->share(function ($c)
		{
			return new NativeFileSessionHandler($c['session_path']);
		});

		$this->container['session_storage'] = $this->container->share(function ($c)
		{
			return new NativeSessionStorage($c['session_options'], $c['session_handler']);
		});

		$this->container['session'] = $this->container->share(function ($c)
		{
			return new Session($c['session_storage']);
		});
	}

	private function dispatcherInit ()
	{
		$this->container['dispatcher'] = $this->container->share(function ($c)
		{
			$dispatcher = new EventDispatcher();
			// матчинг путей, определение контроллера
			$dispatcher->addSubscriber(new RouterListener($c['matcher'], NULL, $c['logger']));
			// локаль
			$dispatcher->addSubscriber(new LocaleListener($c['locale']));
			// подписчик для before
			$dispatcher->addSubscriber(new NamelessListener($c['session'], $c['logger']));
			// приведение респонса к стандартизованному виду
			$dispatcher->addSubscriber(new ResponseListener('UTF-8'));

			return $dispatcher;
		});
	}

	/**
	 * @throws \RuntimeException
	 */
	private function environmentInit ()
	{
		// Установка часового пояса
		date_default_timezone_set($this->container['timezone']);

		if (!function_exists('mb_strlen'))
		{
			throw new \RuntimeException('Need mb_string extension');
		}

		mb_internal_encoding('UTF-8');

		// error/exception reporting
		if ($this->container['environment'] == 'debug')
		{
			error_reporting(-1);
			ini_set('display_errors', 1);
		}
		else
		{
			error_reporting(E_ALL ^ (E_STRICT | E_NOTICE | E_DEPRECATED));
			ini_set('display_errors', 0);
		}
		//die($this->container->environment);
		ErrorHandler::register();

		ExceptionHandler::register(TEMPLATE_PATH, $this->container['templates_extension'], $this->container['environment'], 'UTF-8', $this->container['logger']);
	}

	//TODO: boot -> initializeModules
	public function boot()
	{
		if (!$this->booted)
		{
			foreach ($this->modules as $module)
			{
				$module->boot($this);
			}
			$this->booted = TRUE;
		}
	}

	/**
	 * @param Request $request
	 * @param integer $type
	 * @param boolean $catch
	 *
	 * @return Response
	 */
	public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = TRUE)
	{
		if (!$this->booted) { $this->boot(); }
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
	public function forward($route, array $attributes = array(), array $query = array())
	{
		$defaults = $this->container['routes']->get($route)->getDefaults();
		$attributes['_controller'] = $defaults['_controller'];
		$attributes['_route'] = $route;

		$subRequest = $this->container['request']->duplicate($query, NULL, $attributes);
		return $this->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
	}

	/**
	 * @param Request $request
	 */
	public function run (Request $request = NULL)
	{
		if (is_null($request))
		{
			$request = Request::createFromGlobals();
		}
		$response = $this->handle($request);
		$response->send();
		$this->terminate($request, $response);
    }

}