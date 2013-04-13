<?php

namespace Nameless\Modules\Auto\Provider;

use Nameless\Modules\Database;

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
	 * @param $username
	 *
	 * @return array|false
	 */
	public function getUserByName ($user_name)
	{
		return $this->database->selectOne("SELECT `id`, `password` FROM `tbl_users` WHERE `username` = ?", array($user_name));
	}

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