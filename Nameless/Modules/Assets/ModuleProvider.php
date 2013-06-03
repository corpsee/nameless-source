<?php

/**
 * This file is part of the Nameless framework.
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Assets;

use Nameless\Core\ModuleProvider as BaseModuleProvider;
use Nameless\Modules\Assets\AssetsDispatcher;

/**
 * Assets ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
	const MODULE_NAME = 'Assets';

	public function register ()
	{
		parent::register();

		$this->container['assets.dispatcher'] = $this->container->share(function ($c)
		{
				return new AssetsDispatcher($c);
		});
	}

	public function boot () {}
}
