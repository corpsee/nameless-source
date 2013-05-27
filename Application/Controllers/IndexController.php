<?php

namespace Application\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Controller;

class IndexController extends Controller
{

	public function index ()
	{
		$this->container['localization']->load('index');
		$this->container['localization']->load('index', 'application', 'en');

		$data = array
		(
			'title'       => 'Page title',
			'description' => 'Page description',
			'keywords'    => 'page, keywords',
			'headline'    => 'Page headline',
			'paragraph1'  => $this->container['localization']->get('paragraph'),
			'paragraph2'  => $this->container['localization']->get('paragraph', array(), 'en'),
		);
		return $this->render('index', $data);
	}
}