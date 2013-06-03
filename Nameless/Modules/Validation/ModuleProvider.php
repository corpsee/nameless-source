<?php

/**
 * This file is part of the Nameless framework.
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Validation;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Validation ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
	const MODULE_NAME = 'Validation';

	public function register ()
	{
		parent::register();

		$this->container['validation.validator'] = $this->container->share(function ($c)
		{
			return new Validator($c);
		});
		$this->container['localization']->load('messages', 'validation');
	}

	public function boot () {}
}
