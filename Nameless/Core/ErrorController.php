<?php

namespace Nameless\Core;

use Symfony\Component\Debug\Exception\FlattenException;

class ErrorController extends Controller
{
	//TODO: error style - bootstrap
	public function error (FlattenException $exception)
	{
		return $this->render($exception->getStatusCode(), array(), Template::FILTER_ESCAPE, array(), NULL, NAMELESS_PATH . 'Core' . DS . 'Templates' . DS);
	}
}