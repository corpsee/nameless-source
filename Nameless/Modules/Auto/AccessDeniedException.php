<?php

/**
 * This file is part of the Nameless framework.
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Auto;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AccessDeniedException extends HttpException
{
	/**
	 * @param string|null $message
	 * @param \Exception|null $previous
	 * @param integer $code
	 */
	public function __construct($message = NULL, \Exception $previous = NULL, $code = 0)
	{
		parent::__construct(403, $message, $previous, array(), $code);
	}
}