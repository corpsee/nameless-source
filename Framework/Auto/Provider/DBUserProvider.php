<?php

namespace Framework\Auto\Provider;

use Framework\Database;

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
	public function getUserByName ($username)
	{
		return $this->database->selectOne("SELECT `username`, `password`, `salt`, `groups` FROM `tbl_users` WHERE `username` = ?", array($username));
	}

	public function getUserGroups ($user_name)
	{

	}
}