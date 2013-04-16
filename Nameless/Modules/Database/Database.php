<?php

/*
 * This file is part of the Nameless framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Nameless\Modules\Database;

/**
 * Base database class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Database extends \PDO
{
	//->lastInsertId()
	//result->rowCount();

	/**
	 * @param string $path
	 */
	public function __construct($path)
	{
		parent::__construct($path);
		$this->setAttribute(parent::ATTR_ERRMODE, parent::ERRMODE_EXCEPTION);
	}

	/**
	 * @param string $sql
	 * @param array $params
	 *
	 * @return \PDOStatement
	 */
	public function extendPrepare($sql = '', $params = array())
	{
		$result = $this->prepare($sql);
		$result->execute($params);

		return $result;
	}

	// $result = $database->extendPrepare('SELECT * FROM `table`');
	// while ($row = $result->fetch(\PDO::FETCH_ASSOC)) { }

	/**
	 * @param string $sql
	 * @param array $params
	 *
	 * @return integer
	 */
	public function execute($sql = '', $params = array())
	{
		$result = $this->extendPrepare($sql, $params);

		if(preg_match('#insert#i', $sql))
		{
			return (integer)$this->lastInsertId();
		}
		else
		{
			return (integer)$result->rowCount();
		}
	}

	/**
	 * @param string $sql
	 * @param array $params
	 *
	 * @return array|false
	 */
	public function selectOne($sql = '', $params = array())
	{
		$result = $this->extendPrepare($sql, $params);
		return $result->fetch(parent::FETCH_ASSOC);
	}

	/**
	 * @param string $sql
	 * @param array $params
	 *
	 * @return array|false
	 */
	public function selectMany($sql = '', $params = array())
	{
		$result = $this->extendPrepare($sql, $params);
		return $result->fetchAll(parent::FETCH_ASSOC);
	}

	/**
	 * @param string $sql
	 * @param array $params
	 * @param integer $column
	 *
	 * @return array|false
	 */
	public function selectColumn($sql = '', $params = array(), $column = 0)
	{
		$result = $this->extendPrepare($sql, $params);
		return $result->fetchAll(parent::FETCH_COLUMN, $column);
	}
}