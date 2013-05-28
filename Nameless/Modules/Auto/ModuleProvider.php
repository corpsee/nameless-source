<?php

/**
 * This file is part of the Nameless framework.
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Auto;

use Nameless\Core\ModuleProvider as BaseModuleProvider;
use Nameless\Modules\Auto\User;

class ModuleProvider extends BaseModuleProvider
{
	const MODULE_NAME = 'Auto';

	public function register ()
	{
		parent::register();

		$this->container['auto']['user'] = $this->container->share(function ($c)
		{
			return new User($c['session'], $c['routes'], $c['auto']['access']);
		});
	}

	public function boot () {}
}
