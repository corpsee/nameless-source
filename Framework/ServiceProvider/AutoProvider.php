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
		if (file_exists(ROOT_PATH . 'Application/users.php'))
		{
			$container->users  = include(ROOT_PATH . 'Application/users.php');
		}
		if (file_exists(ROOT_PATH . 'Application/access.php'))
		{
			$container->action_access    = include(ROOT_PATH . 'Application/access.php');
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
