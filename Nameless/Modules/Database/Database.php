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

//TODO: redesign Database
namespace Nameless\Modules\Database;

/**
 * Database class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Database
{
	protected $db_handler;

	//->lastInsertId()
	//result->rowCount();

	public function __construct(\PDO $db_handler)
	{
		$this->db_handler = $db_handler;
	}

	/**
	 * @param string $sql
	 * @param array  $params
	 *
	 * @return \PDOStatement
	 */
	public function executeQuery($sql = '', $params = array())
	{
		$result = $this->db_handler->prepare($sql);
		$result->execute($params);

		return $result;
	}

	// $result = $database->executeQuery('SELECT * FROM `table`');
	// while ($row = $result->fetch(\PDO::FETCH_ASSOC)) { }

	/**
	 * @param string $sql
	 * @param array  $params
	 *
	 * @return integer
	 */
	public function execute($sql = '', $params = array())
	{
		$result = $this->executeQuery($sql, $params);

		if(preg_match('#insert#i', $sql))
		{
			return (integer)$this->db_handler->lastInsertId();
		}
		else
		{
			return (integer)$result->rowCount();
		}
	}

	/**
	 * @param string $sql
	 * @param array  $params
	 *
	 * @return array|false
	 */
	public function selectOne($sql = '', $params = array())
	{
		$result = $this->executeQuery($sql, $params);
		return $result->fetch(\PDO::FETCH_ASSOC);
	}

	/**
	 * @param string $sql
	 * @param array  $params
	 *
	 * @return array|false
	 */
	public function selectMany($sql = '', $params = array())
	{
		$result = $this->executeQuery($sql, $params);
		return $result->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * @param string  $sql
	 * @param array   $params
	 * @param integer $column
	 *
	 * @return array|false
	 */
	public function selectColumn($sql = '', $params = array(), $column = 0)
	{
		$result = $this->executeQuery($sql, $params);
		return $result->fetchAll(\PDO::FETCH_COLUMN, $column);
	}
}