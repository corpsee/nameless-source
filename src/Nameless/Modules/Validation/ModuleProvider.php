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

namespace Nameless\Modules\Validation;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Validation ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
	public function register ($module_path = NULL)
	{
		$module_path = __DIR__ . DS;
		parent::register($module_path);

		$this->container['validation.validator'] = $this->container->share(function ($c)
		{
			return new Validator($c);
		});
		$this->container['localization']->load('messages', 'validation');
	}

	public function boot () {}
}
