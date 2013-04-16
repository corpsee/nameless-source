<?php

namespace Nameless\Modules\Auto;

use Nameless\Core\Kernel;
use Nameless\Core\ModuleProviderInterface;
use Nameless\Modules\Auto\User;

//TODO: вынести сервис-провайдеры внутрь соответствующих модулей
class ModuleProvider implements ModuleProviderInterface
{
	/**
	 * @param \Pimple $container
	 */
	public function register (\Pimple $container)
	{
		if (file_exists(CONFIG_PATH . 'users.php'))
		{
			$container['users'] = include(CONFIG_PATH . 'users.php');
		}
		else
		{
			//TODO: сделать исключение, если файлы настроек не найдены
		}
		if (file_exists(CONFIG_PATH . 'access.php'))
		{
			$container['action_access'] = include(CONFIG_PATH . 'access.php');
		}

		$container['user'] = $container->share(function ($c)
		{
			return new User($c['session'], $c['routes'], $c['action_access']);
		});
	}

	/**
	 * @param Kernel $kernel
	 */
	public function boot (Kernel $kernel)
	{
	}
}
