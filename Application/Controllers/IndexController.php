<?php

namespace Application\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Controller;

class IndexController extends Controller
{

	public function index ()
	{
		$data = array
		(
			'title'       => 'Page title',
			'description' => 'Page description',
			'keywords'    => 'page, keywords',
			'headline'    => 'Page headline',
			'paragraph'   => 'Page text paragraph',
		);
		return $this->render('index', $data);
	}
}