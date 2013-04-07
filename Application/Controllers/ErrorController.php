<?php

namespace Application\Controller;

use Framework\Auto\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorController extends BackendController
{
	public function errorServer ($code)
	{
		//print_r($code); exit;
		switch ((int)$code)
		{
			case 403:
				throw new AccessDeniedException('Access denied!');
				break;
			case 404:
				return $this->notFound('Not found');
				break;
			default:
				throw new HttpException(500, 'Server error!');
		}
	}
}
