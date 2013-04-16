<?php

/*
 * This file is part of the Nameless framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Nameless\Core;

use Symfony\Component\HttpKernel\HttpCache\HttpCache as BaseHttpCache;
use Symfony\Component\HttpFoundation\Request;

class HttpCache extends BaseHttpCache
{
	/**
	 * Handles the Request and delivers the Response.
	 *
	 * @param Request $request The Request objet
	 */
	public function run(Request $request = NULL)
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