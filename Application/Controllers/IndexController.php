<?php

namespace Application\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Controller;

class IndexController extends Controller
{

	public function index ()
	{
		/*$this->container['localization']->load('index');
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
		return $this->render('index', $data);*/

		$styles = array
		(
			'/files/bootstrap/css/bootstrap.min.css',
			'/files/bootstrap/css/bootstrap-responsive.min.css',
		);

		$scripts = array
		(
			'/files/scripts/jquery-1.10.1.min.js',
			'/files/bootstrap/js/bootstrap.min.js',
		);

		$data = array
		(
			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend.min', $styles, 'css', FALSE),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend.min', $scripts, 'js', FALSE),
		);
		return $this->render('index', $data);
	}
}