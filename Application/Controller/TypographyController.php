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
				STYLE_PATH_URL . 'normalize_1.1.0.css',
				STYLE_PATH_URL . 'typography_0.1.css',
			),
			'scripts'      => array
			(
				SCRIPT_PATH_URL . 'jquery/jquery-1.8.3.min.js',
			),
			'page'         => array
			(
				'id' => 0,
				'title' => '',
				'description' => '',
				'keywords' => ''
			),
		);
		return $this->render('typography' . DS . 'typography', $data);
	}
}