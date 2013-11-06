<?php

namespace Nameless\Modules\Database;

interface Driver
{
	public function __construct ($dns, $username = NULL, $password = NULL, array $options);

	public function getDBType ();

	public function getDBName ();

	public function prepare ($query);

	public function query ($query);

	public function quote ($input, $type = \PDO::PARAM_STR);

	public function execute ($statement);

	public function lastInsertId ($name = NULL);

	public function beginTransaction ();

	public function commit ();

	public function rollBack ();

	public function errorCode ();

	public function errorInfo ();
}