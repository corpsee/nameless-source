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

namespace Nameless\Core;

use Symfony\Component\HttpFoundation\Request as BaseRequest;

/**
 * Request class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Request extends BaseRequest
{
    public function getPathInfo()
    {
        if (is_null($this->pathInfo)) {
            $this->pathInfo = $this->preparePathInfo();

            if ('/' !== $this->pathInfo) {
                $this->pathInfo = rtrim($this->pathInfo, '/');
            }
        }
        return $this->pathInfo;
    }
}