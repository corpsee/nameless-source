<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2014. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Database;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Database ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
	public function register ($module_path = NULL)
	{
		$module_path = __DIR__ . DS;
		parent::register($module_path);

		$this->container['database.database'] = $this->container->share(function ($c)
		{
			return new Database
			(
				$c['database.type'],
				$c['database.dns'],
				$c['database.user'],
				$c['database.password'],
				$c['database.persistent'],
				$c['database.compress']
			);
		});
	}

	public function boot () {}
}
