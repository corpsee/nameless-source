<?php

namespace Nameless\Modules\Database;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

class ModuleProvider extends BaseModuleProvider
{
	const MODULE_NAME = 'Database';

	public function register ()
	{
		parent::register();

		$this->container['database'] = $this->container->share(function ($c)
		{
			return new Database($c['database_settings']);
		});
	}

	public function boot () {}
}
