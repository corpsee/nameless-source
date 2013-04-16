<?php

namespace Nameless\Modules\Database;

use Nameless\Core\Kernel;
use Nameless\Core\ModuleProviderInterface;

class ModuleProvider implements ModuleProviderInterface
{
	/**
	 * @param \Pimple $container
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
