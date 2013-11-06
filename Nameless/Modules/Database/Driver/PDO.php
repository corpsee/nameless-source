<?php

namespace Nameless\Modules\Database\Drivers;

use Nameless\Modules\Database\Driver;

class PDO extends Driver
{
	public function __construct ($type, $db_name, $host = NULL, $user = NULL, $password = NULL, $port = NULL, $socket = NULL)
	{
		$dsn = $this->generateDNS($type, $host, $db_name, $port, $socket);
		$this->handler = new \PDO($dsn, $user, $password);

		$this->handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		if (isset($options['persistent']) && $options['persistent'])
		{
			$this->handler->setAttribute(\PDO::ATTR_PERSISTENT, TRUE);
		}

		if (isset($options['compress']) && $options['compress'] && strtolower($options['type']) === 'mysql')
		{
			$this->handler->setAttribute(\PDO::MYSQL_ATTR_COMPRESS, TRUE);
		}
	}

	protected function generateDNS ($type, $db_name, $host = NULL, $port = NULL, $socket = NULL)
	{
		if ('mysql' === $type)
		{
			$dsn = "mysql:host={$host};dbname={$db_name}";

			if (!is_null($port))
			{
				$dsn .= ";port={$port}";
			}

			if (!is_null($socket))
			{
				$dsn .= ";unix_socket={$socket}";
			}
		}
		elseif ('pgsql' === $type)
		{
			$host = !is_null($host) ? "host={$host};" : "";
			$dsn  = "pgsql:{$host}dbname={$db_name}";

			if (!is_null($port))
			{
				$dsn .= ";port={$port}";
			}
		}
		elseif ('sqlite' === $type)
		{
			$dsn = "sqlite:{$db_name}";
		}
		elseif ('mysql' === $type)
		{
			$port = !is_null($port) ? ",{$port}" : "";

			if (in_array('dblib', \PDO::getAvailableDrivers()))
			{
				$dsn = "dblib:host={$host}{$port};dbname={$db_name}";
			}
			else
			{
				$dsn = "sqlsrv:Server={$host}{$port};Database={$db_name}";
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