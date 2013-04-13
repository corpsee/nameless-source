<?php

namespace Framework\ServiceProvider;

use Framework\Container;
use Framework\Kernel;
use Framework\ProviderInterface;
use Framework\Validation\Validator;

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
