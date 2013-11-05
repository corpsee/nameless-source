<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
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
	const MODULE_NAME = 'Database';

	public function register ()
	{
		parent::register();

		$this->container['database.db_handler'] = $this->container->share(function ($c)
		{
			$db_handler = new \PDO($c['database.dns'], $c['database.user'], $c['database.password']);

			$db_handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			if ($c['database.persistent'])
			{
				$db_handler->setAttribute(\PDO::ATTR_PERSISTENT, TRUE);
			}

			if ($c['database.compress'] && strtolower($c['database.type']) === 'mysql')
			{
				$db_handler->setAttribute(\PDO::MYSQL_ATTR_COMPRESS, TRUE);
			}

			return $db_handler;
		});

		$this->container['database.database'] = $this->container->share(function ($c)
		{
			return new Database($c['database.db_handler']);
		});
	}

	public function boot () {}
}
