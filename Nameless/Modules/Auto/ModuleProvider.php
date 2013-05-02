<?php

namespace Nameless\Modules\Auto;

use Nameless\Core\ModuleProvider as BaseModuleProvider;
use Nameless\Modules\Auto\User;

class ModuleProvider extends BaseModuleProvider
{
	const MODULE_NAME = 'Auto';

	public function register ()
	{
		parent::register();

		//TODO: вынести пользователей и права в один файл настроек модуля
		if (file_exists(CONFIG_PATH . 'users.php'))
		{
			$this->container['users'] = include_once(CONFIG_PATH . 'auto_users_configuration.php');
		}

		if (file_exists(CONFIG_PATH . 'access.php'))
		{
			$this->container['action_access'] = include_once(CONFIG_PATH . 'auto_access_configuration.php');
		}

		$this->container['user'] = $this->container->share(function ($c)
		{
			return new User($c['session'], $c['routes'], $c['action_access']);
		});
	}

	public function boot () {}
}
