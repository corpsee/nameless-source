<?php

namespace Framework\ServiceProvider;

use Framework\Container;
use Framework\Kernel;
use Framework\ProviderInterface;
use Framework\Logger;

class MonologProvider implements ProviderInterface
{
	public function register (Container $container)
	{
		$container->logger = $container->service(function ($c)
		{
			$logger = new Logger($c->logger_name);
			$logger->popHandler($c->logger_handler);
		});

		$container->logger_handler = $container->service(function ($c)
		{
			return new StreamHandler($c->log_file, $c->log_level);
		});

		$container->log_level = function ()
		{
			return Logger::DEBUG;
		};

		$container->logger_name = 'application';
	}

	public function boot (Kernel $kernel)
	{
		/*$app->before(function (Request $request) use ($app) {
			$app['monolog']->addInfo('> '.$request->getMethod().' '.$request->getRequestUri());
		});

		$app->error(function (\Exception $e) use ($app) {
			$app['monolog']->addError($e->getMessage());
		}, 255);

		$app->after(function (Request $request, Response $response) use ($app) {
			$app['monolog']->addInfo('< '.$response->getStatusCode());
		});    */
	}
}
