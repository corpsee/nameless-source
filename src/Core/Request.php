<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
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