<?php

namespace Nameless\Core\ServiceProvider;

use Nameless\CoreContainer;
use Nameless\Core\Kernel;
use Nameless\Core\ProviderInterface;
use Nameless\Modules\Database\Database;

class DatabaseProvider implements ProviderInterface
{
	/**
	 * @param Container $container
	 */
	public function register (\Pimple $container)
	{
		$container['database'] = $container->share(function ($c)
		{
			return new Database($c['database_settings']);
		});
	}

	/**
	 * @param Kernel $kernel
	 */
	public function boot (Kernel $kernel)
	{
	}
}
