<?php

namespace Nameless\Modules\Validation;

use Nameless\Core\Kernel;
use Nameless\Core\ModuleProviderInterface;

class ModuleProvider implements ModuleProviderInterface
{
	/**
	 * @param \Pimple $container
	 */
	public function register (\Pimple $container)
	{
		//TODO: исключение / значение массива по умолчанию
		if (file_exists(CONFIG_PATH . 'validation.php'))
		{
			$container['validation_rules'] = include_once(CONFIG_PATH . 'validation.php');
		}

		$container['validator'] = $container->share(function ($c)
		{
			return new Validator($c);
		});
	}

	/**
	 * @param Kernel $kernel
	 */
	public function boot (Kernel $kernel)
	{
	}
}
