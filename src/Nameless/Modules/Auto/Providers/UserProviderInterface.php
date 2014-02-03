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

namespace Nameless\Modules\Auto\Providers;

/**
 * UserProviderInterface interface
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
Interface UserProviderInterface
{
	/**
	 * @param string $user_name
	 *
	 * @return array|false
	 */
	public function getUserByName ($user_name);

	/**
	 * @param string $user_name
	 *
	 * @return array|false
	 */
	public function getUserGroups ($user_name);
}