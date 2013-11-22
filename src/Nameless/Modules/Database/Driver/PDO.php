<?php

namespace Nameless\Modules\Database\Drivers;

use Nameless\Modules\Database\Driver;

//TODO: charset
class PDO extends Driver
{
	public function __construct ($type, $name, $host = NULL, $user = NULL, $password = NULL, $port = NULL, array $options = array())
	{
		$dsn = $this->generateDSN($type, $host, $name, $port);

		$this->handler = new \PDO($dsn, $user, $password, $options);
		$this->handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	protected function generateDSN ($type, $name, $host = NULL, $port = NULL)
	{
		if ('mysql' === $type)
		{
			$dsn = "mysql:host={$host};dbname={$name}";

			if (!is_null($port))
			{
				$dsn .= ";port={$port}";
			}
		}
		elseif ('pgsql' === $type)
		{
			$host = !is_null($host) ? "host={$host};" : "";
			$dsn  = "pgsql:{$host}dbname={$name}";

			if (!is_null($port))
			{
				$dsn .= ";port={$port}";
			}
		}
		elseif ('sqlite' === $type)
		{
			$dsn = "sqlite:{$name}";
		}
		elseif ('mysql' === $type)
		{
			$port = !is_null($port) ? ",{$port}" : "";

			if (in_array('dblib', \PDO::getAvailableDrivers()))
			{
				$dsn = "dblib:host={$host}{$port};dbname={$name}";
			}
			else
			{
				$dsn = "sqlsrv:Server={$host}{$port};Database={$name}";
			}
		}
		else
		{
			throw new \InvalidArgumentException('Database type "' . $type . '" don`t support.');
		}
		return $dsn;
	}

	public function getDBType ()
	{

	}

	public function getDBName ()
	{

	}

	public function prepare ($query)
	{

	}

	public function query ($query)
	{

	}

	public function quote ($input, $type = \PDO::PARAM_STR)
	{

	}

	public function execute ($statement)
	{

	}

	public function lastInsertId ($name = NULL)
	{

	}

	public function beginTransaction ()
	{

	}

	public function commit ()
	{

	}

	public function rollBack ()
	{

	}

	public function errorCode ()
	{

	}

	public function errorInfo ()
	{

	}
}