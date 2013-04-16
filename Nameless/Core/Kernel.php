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
		$this->container = new \Pimple();
		$this->container['kernel'] = $this;

		//TODO: добавить конфигурацию поумолчанию и мердж с пользовательской
		// configurations/routes
		/*if (file_exists(CONFIG_PATH . 'configuration.php'))
		{
			$configs = include_once(CONFIG_PATH . 'configuration.php');
		}

		if (file_exists(CONFIG_PATH . 'routes.php'))
		{
			$routes = include_once(CONFIG_PATH . 'routes.php');
		}*/

		$configs = include_once(CONFIG_PATH . 'configuration.php');
		$routes  = include_once(CONFIG_PATH . 'routes.php');

		// configuration
		foreach ($configs as $config_option => $config)
		{
			$this->container[$config_option] = $config;
		}

		// start point
		if ($this->container['environment'] == 'debug')
		{
			$this->start_time = microtime(TRUE);
		}

		// routers
		$this->container['routes'] = $this->container->share(function ($c)
		{
			return new RouteCollection();
		});
		// routes
		foreach ($routes as $route_name => &$route)
		{
			$route['defaults']     = isset($route['defaults']) ? $route['defaults'] : array();
			$route['requirements'] = isset($route['requirements']) ? $route['requirements'] : array();
			$route['options']      = isset($route['options']) ? $route['options'] : array();

			$this->container['routes']->add($route_name, new Route($route['pattern'], $route['defaults'], $route['requirements'], $route['options']));
		}
		unset($route);

		$this->container['logger'] = NULL;

		// services
		foreach ($this->container['services'] as $service)
		{
			$module_provider_name = 'Nameless\\Modules\\' . $service . '\\ModuleProvider';
			$module_provider = new $module_provider_name;

			if ($module_provider instanceof ModuleProviderInterface)
			{
				$this->modules[] = $module_provider;
				$module_provider->register($this->container);
			}
			else
			{
				//TODO: исключение
			}
		}

		// sessions
		$this->container['session_options'] = array();
		$this->container['session_default_locale'] = $this->container['locale'];
		$this->container['session_path'] = '';
		// session handler
		$this->container['session_handler'] = $this->container->share(function ($c)
		{
			return new NativeFileSessionHandler($c['session_path']);
		});
		// session storage
		$this->container['session_storage'] = $this->container->share(function ($c)
		{
			return new NativeSessionStorage($c['session_options'], $c['session_handler']);
		});
		// session
		$this->container['session'] = $this->container->share(function ($c)
		{
			return new Session($c['session_storage']);
		});

		// matcher (router)
		$this->container['matcher'] = $this->container->share(function ($c)
		{
			$context  = new RequestContext($c['http_port'], $c['https_port']);
			return new UrlMatcher($c['routes'], $context);
		});
		// controller resolver
		$this->container['resolver'] = $this->container->share(function ($c)
		{
			return new ControllerResolver($c, $c['logger']);
		});

		// dispatcher
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
			$dispatcher->addSubscriber(new ResponseListener($c['charset']));

			return $dispatcher;
		});

		$this->init();

		parent::__construct($this->container['dispatcher'], $this->container['resolver']);
	}

	/**
	 * @throws \RuntimeException
	 */
	private function init ()
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

		ExceptionHandler::register($this->container['templates_path'], $this->container['templates_extension'], $this->container['environment'], 'UTF-8', $this->container['logger']);
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