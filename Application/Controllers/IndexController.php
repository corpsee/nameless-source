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

		//TODO: сделать параметр compress для отдельных скриптов
		//TODO: сделать параметр для набора: объеденять ли файлы? (combine)
		$styles = array
		(
			'/files/css/bootstrap.css',
			'/files/css/bootstrap-responsive.css',
			'/files/css/nameless.less',
		);

		$scripts = array
		(
			'/files/js/jquery-1.10.1.js',
			'/files/js/bootstrap.js',
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