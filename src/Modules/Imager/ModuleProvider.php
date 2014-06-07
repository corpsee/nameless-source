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

namespace Nameless\Modules\Imager;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Imager ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
	public function register ($module_path = NULL)
	{
		$module_path = __DIR__ . DS;
		parent::register($module_path);

		$this->container['imager.driver'] = $this->container->share(function ($c)
		{
			$driver = '\\' . __NAMESPACE__ . '\\' . $c['imager.driver_name'] . 'Driver';
			return new $driver();
		});

		$this->container['imager.image'] = function ($c)
		{
			return new Image($c['imager.driver']);
		};
	}

	public function boot () {}
}
