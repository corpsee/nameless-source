<?php

namespace Nameless\Core;

use Symfony\Component\HttpFoundation\Request as BaseRequest;

class Request extends BaseRequest
{
	public function getPathInfo ()
	{
		if (is_null($this->pathInfo))
		{
			$this->pathInfo = rtrim($this->preparePathInfo(), '/');
		}
		return $this->pathInfo;
	}
}