<?php

namespace Application\Controllers;

use Nameless\Core\Template;
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
			'/files/lib/bootstrap/2.3.2/css/bootstrap.css',
			'/files/css/nameless.less',
		);

		$scripts = array
		(
			'/files/lib/jquery/1.10.2/jquery.js',
			'/files/lib/bootstrap/2.3.2/js/bootstrap.js',
		);

		$data = array
		(
			'title'       => 'Nameless framework demo page',
			'description' => 'Nameless framework demo page',
			'keywords'    => 'Nameless framework demo page',
			'h2_en'       => $this->container['localization']->get('h2', 'en'),
			'p_en'        => $this->container['localization']->get('p', 'en'),
			'btn_en'      => $this->container['localization']->get('btn', 'en'),
			'h2_ru'       => $this->container['localization']->get('h2'),
			'p_ru'        => $this->container['localization']->get('p'),
			'btn_ru'      => $this->container['localization']->get('btn'),
			'styles'      => $this->container['assets.dispatcher']->getAssets('frontend', $styles),
			'scripts'     => $this->container['assets.dispatcher']->getAssets('frontend', $scripts),
			'subtemplate' => 'subindex',
		);
		return $this->render('index', $data, Template::FILTER_XSS);

		/*$image = $this->container['imager.image']
			->open(PUBLIC_PATH . 'observer_origin.jpg')
			->resize(1000)->save(PUBLIC_PATH . 'observer_origin_resize_1000.jpg', 'image/jpeg')
			->crop(300, 200)
			->save(PUBLIC_PATH . 'observer_origin_crop_200x100.jpg', 'image/jpeg')
			->grayscale()
			->save(PUBLIC_PATH . 'observer_origin_gray.jpg', 'image/jpeg');
		exit();*/

	}
}