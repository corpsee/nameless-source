<?php

namespace Framework\ServiceProvider;

use Framework\Container;
use Framework\Kernel;
use Framework\ProviderInterface;
use Framework\Auto\User;

class AutoProvider implements ProviderInterface
{
	/**
	 * @param Container $container
	 */
	public function register (Container $container)
	{
		if (file_exists(CONFIG_PATH . 'users.php'))
		{
			$container->users = include(CONFIG_PATH . 'users.php');
		}
		if (file_exists(CONFIG_PATH . 'access.php'))
		{
			$container->action_access = include(CONFIG_PATH . 'access.php');
		}

		$container->user = $container->service(function ($c)
		{
			return new User($c->session, $c->routes, $c->action_access);
		});
	}

	/**
	 * @param Kernel $kernel
	 */
	public function boot (Kernel $kernel)
	{
	}
}
