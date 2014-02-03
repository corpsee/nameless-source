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

namespace Nameless\Modules\Assets;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Assets ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
	//TODO: assets lib packeges like: $container['assets.dispatcher']->get['jquery']
	public function register ($module_path = NULL)
	{
		$module_path = __DIR__ . DS;
		parent::register($module_path);

		$this->container['assets.dispatcher'] = $this->container->share(function ($c)
		{
			return new AssetsDispatcher($c);
		});
	}

	public function boot () {}
}
