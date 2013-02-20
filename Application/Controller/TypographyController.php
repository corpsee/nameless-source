<?php

namespace Application\Controller;

use Framework\Controller;
use Symfony\Component\HttpFoundation\Response;

class TypographyController extends Controller
{

	public function index ()
	{
		$data = array
		(
			'styles'       => array
			(
				STYLE_PATH_URL . 'normalize-2.0.1.css',
				STYLE_PATH_URL . 'typography-0.1.css',
			),
			'scripts'      => array
			(
				/*SCRIPT_PATH_URL . 'jquery/jquery-1.8.3.min.js',*/
			),
			'page'         => array
			(
				'id' => 0,
				'title' => '',
				'description' => '',
				'keywords' => ''
			),
		);
		return $this->render('typography', $data);
	}
}