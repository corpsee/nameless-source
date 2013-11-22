<?php

namespace Nameless\Core;

use Symfony\Component\Debug\Exception\FlattenException;

class ErrorController extends Controller
{
	//TODO: error style - bootstrap
	public function error (FlattenException $exception)
	{
		$data = array
		(
			'styles' => array('/files/lib/bootstrap/2.3.2/css/bootstrap.css',),
		);
		return $this->render($exception->getStatusCode(), $data, Template::FILTER_ESCAPE, array(), NULL, NAMELESS_PATH . 'Core' . DS . 'templates' . DS);
	}
}