<?php

namespace Framework\ServiceProvider;

use Framework\Container;
use Framework\Kernel;
use Framework\ProviderInterface;
use Framework\Database\Database;

class DatabaseProvider implements ProviderInterface
{
	/**
	 * @param Container $container
	 */
	public function register (Container $container)
	{
		$container->database = $container->service(function ($c)
		{
			return new Database($c->database_settings);
		});
	}

	/**
	 * @param Kernel $kernel
	 */
	public function boot (Kernel $kernel)
	{
	}
}
