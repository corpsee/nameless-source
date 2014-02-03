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

namespace Nameless\Modules\Auto;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * AccessDeniedException class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AccessDeniedException extends HttpException
{
	/**
	 * @param string $message
	 * @param \Exception $previous
	 * @param integer $code
	 */
	public function __construct($message = NULL, \Exception $previous = NULL, $code = 0)
	{
		parent::__construct(403, $message, $previous, array(), $code);
	}
}