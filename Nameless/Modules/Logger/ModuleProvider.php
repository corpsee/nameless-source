<?php

namespace Nameless\Modules\Logger;

use Nameless\Core\Kernel;
use Nameless\Core\ModuleProviderInterface;
use Monolog\Handler\StreamHandler;

class ModuleProvider implements ModuleProviderInterface
{
	public function register (\Pimple $container)
	{
		$container['logger'] = $container->share(function ($c)
		{
			$logger = new Logger($c['logger_name']);
			$logger->pushHandler($c-['logger_handler']);
			return $logger;
		});

		$container['logger_handler'] = $container->share(function ($c)
		{
			return new StreamHandler($c['log_file'], $c['log_level']);
		});

		$container['log_level'] = function ()
		{
			return Logger::DEBUG;
		};

		$container['logger_name'] = 'application';
		$container['log_file'] = $container['log_path'] . $container['logger_name'] . '.log';
	}

	public function boot (Kernel $kernel)
	{
	}
}
