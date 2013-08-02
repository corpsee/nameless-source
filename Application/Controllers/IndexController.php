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

		$styles = array
		(
			'/files/lib/bootstrap/2.3.2/css/bootstrap.min.css',
			'/files/lib/bootstrap/2.3.2/css/bootstrap-responsive.min.css',
			'/files/css/nameless.less',
		);

		$scripts = array
		(
			'/files/lib/jquery/1.10.2/jquery.min.js',
			'/files/lib/bootstrap/2.3.2/js/bootstrap.min.js',
		);

		$data = array
		(
			'title'       => 'Nameless framework demo page',
			'description' => 'Nameless framework demo page',
			'keywords'    => 'Nameless framework demo page',
			'h2_en'       => $this->container['localization']->get('h2', array(), 'en'),
			'p_en'        => $this->container['localization']->get('p', array(), 'en'),
			'btn_en'      => $this->container['localization']->get('btn', array(), 'en'),
			'h2_ru'       => $this->container['localization']->get('h2'),
			'p_ru'        => $this->container['localization']->get('p'),
			'btn_ru'      => $this->container['localization']->get('btn'),
			'styles'      => $this->container['assets.dispatcher']->getAssets('frontend', $styles),
			'scripts'     => $this->container['assets.dispatcher']->getAssets('frontend', $scripts),
		);
		return $this->render('index', $data);
	}
}