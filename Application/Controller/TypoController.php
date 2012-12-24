<?php

namespace Application\Controller;

use Framework\Controller;
use Symfony\Component\HttpFoundation\Response;

class TypoController extends Controller
{

	public function index ()
	{
		$data = array
		(
			'styles'       => array
			(
				S_FILE_PATH . 'normalize-2.0.1.css',
				S_FILE_PATH . 'newstyle.css',
			),
			'scripts'      => array
			(
				J_FILE_PATH . 'jquery/jquery-1.8.3.min.js',
			),
			'page'         => array
			(
				'id' => 0,
				'title' => '',
				'description' => '',
				'keywords' => ''
			),
			'subtemplates' => array('content' => 'typo'),
		);
		return $this->render('front_page', $data);
	}
}