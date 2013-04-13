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
			'index_text' => 'Index page!',
		);
		return $this->render('index', $data);
	}
}