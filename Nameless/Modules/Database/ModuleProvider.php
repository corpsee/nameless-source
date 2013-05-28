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

		$this->container['database']['database'] = $this->container->share(function ($c)
		{
			return new Database
			(
				$c['database']['type'],
				$c['database']['dns'],
				$c['database']['user'],
				$c['database']['password'],
				$c['database']['persistent'],
				$c['database']['compress']
			);
		});
	}

	public function boot () {}
}
