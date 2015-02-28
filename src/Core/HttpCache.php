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

use Symfony\Component\HttpKernel\HttpCache\HttpCache as BaseHttpCache;
use Symfony\Component\HttpFoundation\Request as BaseRequest;

/**
 * HttpCache class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class HttpCache extends BaseHttpCache
{
    /**
     * Handles the Request and delivers the Response.
     *
     * @param BaseRequest $request The Request objet
     */
    public function run(BaseRequest $request = null)
    {
        if (is_null($request)) {
            $request = Request::createFromGlobals();
        }
        $response = $this->handle($request);
        $response->send();
        $this->terminate($request, $response);
    }
}