<?php

namespace Framework\ServiceProvider;

use Framework\Container;
use Framework\Kernel;
use Framework\ProviderInterface;
use Framework\Logger\Logger;
use Monolog\Handler\StreamHandler;

class MonologProvider implements ProviderInterface
{
	public function register (Container $container)
	{
		$container->logger = $container->service(function ($c)
		{
			$logger = new Logger($c->logger_name);
			$logger->pushHandler($c->logger_handler);
			return $logger;
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
		$container->log_file = $container->log_path . $container->logger_name . '.log';
	}

	public function boot (Kernel $kernel)
	{
	}
}
