<?php

namespace Nameless\Modules\Auto\Providers;

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