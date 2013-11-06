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

	protected $fetch_method;

	//->lastInsertId()
	//result->rowCount();

	public function __construct(Driver $db_handler, $fetch_method = \PDO::FETCH_ASSOC)
	{
		$this->db_handler = $db_handler;
		$this->setFetchMethod($fetch_method);
	}

	public function getFetchMethod ()
	{
		return $this->fetch_method;
	}

	public function setFetchMethod ($fetch_method)
	{
		if (!in_array($fetch_method, array
		(
			\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC, \PDO::FETCH_BOTH, \PDO::FETCH_BOUND,
			\PDO::FETCH_CLASS, \PDO::FETCH_CLASS | \PDO::FETCH_CLASSTYPE,
			\PDO::FETCH_INTO, \PDO::FETCH_LAZY, \PDO::FETCH_NUM, \PDO::FETCH_OBJ
		)))
		{
			throw new \InvalidArgumentException('Invalid fetch method!');
		}
		$this->fetch_method = $fetch_method;
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
		return $result->fetch($this->fetch_method);
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
		return $result->fetchAll($this->fetch_method);
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