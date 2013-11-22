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

	public function __construct(Driver $db_handler)
	{
		$this->db_handler = $db_handler;
	}
}