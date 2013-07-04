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

namespace Nameless\Modules\Auto\Providers;

use Nameless\Modules\Database;

/**
 * DBUserProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class DBUserProvider implements UserProviderInterface
{
	/**
	 * @var Database
	 */
	private $database;

	/**
	 * @param Database $database
	 */
	public function  __construct(Database $database)
	{
		$this->database = $database;
	}

	/**
	 * @param string $user_name
	 *
	 * @return array|FALSE
	 */
	public function getUserByName ($user_name)
	{
		return $this->database->selectOne("SELECT `id`, `password` FROM `tbl_users` WHERE `username` = ?", array($user_name));
	}

	/**
	 * @param string $user_name
	 *
	 * @return array|false
	 */
	public function getUserGroups ($user_name)
	{
		$groups = $this->database->selectOne("SELECT `groups` FROM `tbl_users` WHERE `username` = ?", array($user_name));

		if ($groups)
		{
			return stringToArray($groups['groups']);
		}
		return FALSE;
	}
}