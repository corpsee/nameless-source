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
	public function run(BaseRequest $request = NULL)
	{
		if (is_null($request))
		{
			$request = Request::createFromGlobals();
		}
		$response = $this->handle($request);
		$response->send();
		$this->terminate($request, $response);
	}
}