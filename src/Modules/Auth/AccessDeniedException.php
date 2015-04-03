<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Auth;

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
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(403, $message, $previous, [], $code);
    }
}
