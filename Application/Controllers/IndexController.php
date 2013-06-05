<?php

namespace Application\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Controller;

class IndexController extends Controller
{

	public function index ()
	{
		/*
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
		*/

		$styles = array
		(
			'/files/css/bootstrap.css',
			'/files/css/bootstrap-responsive.css',
		);

		$scripts = array
		(
			'/files/js/jquery-1.10.1.js',
			'/files/js/bootstrap.js',
		);

		$data = array
		(
			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $styles),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', $scripts),
		);
		return $this->render('index', $data);
	}
}