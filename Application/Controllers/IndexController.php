<?php

namespace Application\Controller;

use Symfony\Component\HttpFoundation\Response;

class IndexController extends FrontendController
{

	public function index ()
	{
		$data = array
		(
			'index_text' => 'Index page!',
		);
		return $this->render('index', $data);
	}
}