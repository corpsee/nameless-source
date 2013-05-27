<?php

/**
 * This file is part of the Nameless framework.
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

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
			return new Database
			(
				$c['database_settings']['type'],
				$c['database_settings']['dns'],
				$c['database_settings']['user'],
				$c['database_settings']['password'],
				$c['database_settings']['persistent'],
				$c['database_settings']['compress']
			);
		});
	}

	public function boot () {}
}
