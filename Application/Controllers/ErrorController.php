<?php

namespace Application\Controllers;

use Nameless\Modules\Auto\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Nameless\Core\Controller;

//TODO: сделать слушателем (вынести функционал для продакшена из ExceptionHandler)
class ErrorController extends Controller
{
	public function errorServer ($code)
	{
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
