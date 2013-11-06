<?php

namespace Nameless\Modules\Database;

abstract class Driver
{
	protected $handler;

	abstract public function __construct ($type, $db_name, $host = NULL, $user = NULL, $password = NULL, $port = NULL, $socket = NULL);

	abstract public function getDBType ();

	abstract public function getDBName ();

	abstract public function prepare ($query);

	abstract public function query ($query);

	abstract public function quote ($input, $type = \PDO::PARAM_STR);

	abstract public function execute ($statement);

	abstract public function lastInsertId ($name = NULL);

	abstract public function beginTransaction ();

	abstract public function commit ();

	abstract public function rollBack ();

	abstract public function errorCode ();

	abstract public function errorInfo ();
}