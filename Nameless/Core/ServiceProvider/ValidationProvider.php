<?php

namespace Nameless\Core\ServiceProvider;

use Nameless\Core\Container;
use Nameless\Core\Kernel;
use Nameless\Core\ProviderInterface;
use Nameless\Modules\Validation\Validator;

class ValidationProvider implements ProviderInterface
{
	/**
	 * @param Container $container
	 */
	public function register (\Pimple $container)
	{
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
