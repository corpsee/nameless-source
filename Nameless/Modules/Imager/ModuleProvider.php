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

namespace Nameless\Modules\Imager;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Imager ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
	const MODULE_NAME = 'Imager';

	public function register ()
	{
		parent::register();

		$this->container['imager.driver'] = $this->container->share(function ($c)
		{
			$driver = $c['imager.driver_name'] . 'Driver';
			return new $driver();
		});

		$this->container['imager.image'] = $this->container->share(function ($c)
		{
			return new Image($c['imager.driver']);
		});
	}

	public function boot () {}
}
