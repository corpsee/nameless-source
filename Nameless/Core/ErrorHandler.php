<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless\Core
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Core;

if (!defined('ENT_SUBSTITUTE'))
{
	define('ENT_SUBSTITUTE', 8);
}

/**
 * Class ErrorHandler
 */
class ErrorHandler
{
	/**
	 * @var array
	 */
	protected $levels = array
	(
		E_WARNING           => 'Warning',
		E_NOTICE            => 'Notice',
		E_USER_ERROR        => 'User Error',
		E_USER_WARNING      => 'User Warning',
		E_USER_NOTICE       => 'User Notice',
		E_STRICT            => 'Runtime Notice',
		E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
		E_DEPRECATED        => 'Deprecated',
		E_USER_DEPRECATED   => 'User Deprecated',
	);

	public static function register ()
	{
		$handler = new static();
		set_error_handler(array($handler, 'handleError'));
		register_shutdown_function(array($handler, 'handleShutdown'));
	}

	/**
	 * @param integer $level
	 * @param string  $message
	 * @param string  $file
	 * @param integer $line
	 *
	 * @throws \ErrorException
	 */
	public function handleError ($level, $message, $file, $line)
	{
		if (error_reporting() & $level)
		{
			throw new \ErrorException(sprintf('%s: %s in %s line %d', isset($this->levels[$level]) ? $this->levels[$level] : $level, $message, $file, $line), 0, $level, $file, $line);
		}
	}

	/**
	 * @throws \ErrorException
	 */
	public function handleShutdown ()
	{
		$error = error_get_last();
		if ($error['type'] === E_ERROR)
		{
			throw new \ErrorException(sprintf('%s: %s in %s line %d', $error['type'], $error['message'], $error['file'], $error['line']), 0, $error['type'], $error['file'], $error['line']);
		}
	}
}
