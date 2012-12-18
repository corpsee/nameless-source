<?php

/*
 * This file is part of the Framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Framework;

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
use Symfony\Component\HttpKernel\Debug\ExceptionHandler;
use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Route;
use Framework\Auto\User;

class Kernel extends HttpKernel implements HttpKernelInterface
{
	/**
	 * @var array
	 */
	private $providers = array();

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
		$this->container = new Container();
		$this->container->kernel = $this;

		// configurations/routes
		$configs = include(ROOT_PATH . 'Application/configuration.php');
		$routes  = include(ROOT_PATH . 'Application/routes.php');
		//$this->container->users  = include(ROOT_PATH . 'Application/users.php');

		// configuration
		foreach ($configs as $config_option => $config)
		{
			$this->container->$config_option = $config;
		}

		//TODO: вынести в регистрацию модулей
		$this->container->validation_rules = include(ROOT_PATH . 'Application/validation.php');

		$this->init();

		// start point
		if ($this->container->debug)
		{
			$this->start_time = microtime(TRUE);
		}

		// routers
		$this->container->routes = $this->container->service(function ($c)
		{
			return new RouteCollection();
		});
		// routes
		foreach ($routes as $route_name => &$route)
		{
			//TODO: довести до ума, добавить проверку остальных параметров
			if (!isset($route['requirements'])) { $route['requirements'] = array(); }
			if (!isset($route['options'])) { $route['options'] = array(); }

			$this->container->routes->add($route_name, new Route($route['pattern'], $route['defaults'], $route['requirements'], $route['options']));
		}
		unset($route);

		// services
		foreach ($this->container->services as $service)
		{
			$service_provider = new $service();
			$this->providers[] = $service_provider;
			$service_provider->register($this->container);
		}

		$this->container->logger  = NULL;
		$this->container->database = $this->container->service(function ($c)
		{
			return new Database($c->database_settings);
		});

		/*$this->container->user = $this->container->service(function ($c)
		{
			return new User($c);
		});*/

		// sessions
		$this->container->session_options = array();
		$this->container->session_default_locale = $this->container->locale;
		$this->container->session_path = '';
		// session handler
		$this->container->session_handler = $this->container->service(function ($c)
		{
			return new NativeFileSessionHandler($c->session_path);
		});
		// session storage
		$this->container->session_storage = $this->container->service(function ($c)
		{
			return new NativeSessionStorage($c->session_options, $c->session_handler);
		});
		// session
		$this->container->session = $this->container->service(function ($c)
		{
			return new Session($c->session_storage);
		});

		// matcher (router)
		$this->container->matcher = $this->container->service(function ($c)
		{
			$context  = new RequestContext($c->http_port, $c->https_port);
			return new UrlMatcher($c->routes, $context);
		});
		// controller resolver
		$this->container->resolver = $this->container->service(function ($c)
		{
			return new ControllerResolver($c);
		});

		// dispatcher
		$this->container->dispatcher = $this->container->service(function ($c)
		{
			$dispatcher = new EventDispatcher();
			// матчинг путей, определение контроллера
			$dispatcher->addSubscriber(new RouterListener($c->matcher));
			// локаль
			$dispatcher->addSubscriber(new LocaleListener($c->locale));
			// подписчик для before
			$dispatcher->addSubscriber(new FrameworkListener($c->session/*, $c->logger*/));
			// приведение респонса к стандартизованному виду
			$dispatcher->addSubscriber(new ResponseListener($c->charset));

			return $dispatcher;
		});

		$this->container->validator = $this->container->service(function ($c)
		{
			return new Validator($c);
		});

		parent::__construct($this->container->dispatcher, $this->container->resolver);
	}

	/**
	 * @throws \RuntimeException
	 */
	private function init ()
	{
		// Установка часового пояса
		date_default_timezone_set($this->container->timezone);

		if (!function_exists('mb_strlen'))
		{
			throw new \RuntimeException('Need mb_string extension');
		}

		mb_internal_encoding('UTF-8');

		// error/exception reporting
		if ($this->container->debug)
		{
			ini_set('display_errors', 1);
			error_reporting(-1);
			if ('cli' !== php_sapi_name())
			{
				ExceptionHandler::register($this->container->debug);
			}
		}
		else
		{
			ini_set('display_errors', 0);
			ExceptionHandlerProduction::register($this->container->templates_path, $this->container->templates_extension);
		}
		ErrorHandler::register();
	}

	public function boot()
	{
		if (!$this->booted)
		{
			foreach ($this->providers as $provider)
			{
				$provider->boot($this);
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
		$this->container->request = $request;

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
		$defaults = $this->container->routes->get($route)->getDefaults();
		$attributes['_controller'] = $defaults['_controller'];
		$attributes['_route'] = $route;

		$subRequest = $this->container->request->duplicate($query, NULL, $attributes);
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