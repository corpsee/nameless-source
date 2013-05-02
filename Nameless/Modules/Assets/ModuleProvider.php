<?php

namespace Nameless\Modules\Assets;

use Nameless\Core\ModuleProvider as BaseModuleProvider;
use Nameless\Modules\Assets\AssetsDispatcher;

class ModuleProvider extends BaseModuleProvider
{
	const MODULE_NAME = 'Assets';

	public function register ()
	{
		parent::register();

		$this->container['assets_dispatcher'] = $this->container->share(function ($c)
		{
			return new AssetsDispatcher($c);
		});
	}

	public function boot () {}
}
