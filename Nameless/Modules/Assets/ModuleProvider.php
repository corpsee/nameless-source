<?php

namespace Nameless\Modules\Assets;

use Nameless\Core\Kernel;
use Nameless\Core\ModuleProviderInterface;
use Nameless\Modules\Assets\AssetsDispatcher;

class ModuleProvider implements ModuleProviderInterface
{
	/**
	 * @param \Pimple $container
	 */
	public function register (\Pimple $container)
	{
		$container['assets_dispatcher'] = $container->share(function ($c)
		{
			return new AssetsDispatcher($c);
		});
	}

	/**
	 * @param Kernel $kernel
	 */
	public function boot (Kernel $kernel)
	{
	}
}
