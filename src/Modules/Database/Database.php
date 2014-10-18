<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2014. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

//TODO: redesign Database
namespace Nameless\Modules\Database;

/**
 * Database class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Database extends \PDO
{
    //->lastInsertId()
    //result->rowCount();

    /**
     * @param string $db_type
     * @param string $dns
     * @param string $user
     * @param string $password
     * @param boolean $persistent
     * @param boolean $compress
     */
    public function __construct($db_type, $dns, $user = null, $password = null, $persistent = false, $compress = false)
    {
        $attributes = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION];

        if ($persistent) {
            $attributes[\PDO::ATTR_PERSISTENT] = true;
        }

        if (strtolower($db_type) === 'mysql' && $compress) {
            $attributes[\PDO::MYSQL_ATTR_COMPRESS] = true;
        }

        parent::__construct($dns, $user, $password, $attributes);
    }

    /**
     * @param string $sql
     * @param array $params
     *
     * @return \PDOStatement
     */
    public function extendPrepare($sql = '', $params = [])
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
    public function execute($sql = '', $params = [])
    {
        $result = $this->extendPrepare($sql, $params);

        if (preg_match('#insert#i', $sql)) {
            return (integer)$this->lastInsertId();
        } else {
            return (integer)$result->rowCount();
        }
    }

    /**
     * @param string $sql
     * @param array $params
     *
     * @return array|false
     */
    public function selectOne($sql = '', $params = [])
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
    public function selectMany($sql = '', $params = [])
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
    public function selectColumn($sql = '', $params = [], $column = 0)
    {
        $result = $this->extendPrepare($sql, $params);
        return $result->fetchAll(parent::FETCH_COLUMN, $column);
    }
}