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

namespace Nameless\Modules\Assets;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Assets ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
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
