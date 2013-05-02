<?php

namespace Nameless\Modules\Validation;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

class ModuleProvider extends BaseModuleProvider
{
	const MODULE_NAME = 'Validation';

	public function register ()
	{
		parent::register();

		//TODO: вынести в общие настройки модуля
		if (file_exists(CONFIG_PATH . 'validation.php'))
		{
			$this->container['validation_rules'] = include_once(CONFIG_PATH . 'validation.php');
		}

		$this->container['validator'] = $this->container->share(function ($c)
		{
			return new Validator($c);
		});
	}

	public function boot () {}
}
