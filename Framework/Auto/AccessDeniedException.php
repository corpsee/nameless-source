<?php

namespace Framework\Auto;
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