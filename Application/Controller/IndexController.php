<?php

namespace Application\Controller;

use Framework\Controller;
use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Tag;
use Symfony\Component\HttpFoundation\Response;

use Assetic\Asset\AssetCollection;
use Assetic\Filter\Yui\CssCompressorFilter;
use Assetic\Filter\Yui\JsCompressorFilter;
use Assetic\Asset\FileAsset;

class IndexController extends Controller
{
	private function setAssetJs ($name, $scripts)
	{
		$scripts_collection = array();

		foreach ($scripts as $script)
		{
			$scripts_collection[] = new FileAsset($script);
		}

		$js = new AssetCollection
		(
			$scripts_collection,
			array(new JsCompressorFilter(ROOT_PATH . 'yuicompressor-2.4.jar', 'C:\Program Files\Java\jre6\bin\java.exe'))
		);

		file_put_contents(ROOT_PATH . 'Public/Files/j/' . $name . '.js', $js->dump());

		return J_FILE_PATH . $name . '.js';
	}

	private function setAssetCss ($name, $styles)
	{
		$styles_collection = array();

		foreach ($styles as $style)
		{
			$styles_collection[] = new FileAsset($style);
		}

		$css = new AssetCollection
		(
			$styles_collection,
			array(new CssCompressorFilter(ROOT_PATH . 'yuicompressor-2.4.jar', 'C:\Program Files\Java\jre6\bin\java.exe'))
		);

		file_put_contents(ROOT_PATH . 'Public/Files/s/' . $name . '.css', $css->dump());

		return S_FILE_PATH . $name . '.css';
	}

	public function index ()
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());
		$tag_model     = new Tag($this->getDatabase());

		$response = new Response();

		$lm_pictures = $gallery_model->getLastModifyDate();
		$lm_tags     = $tag_model->getLastModifyDate();
		$last_modify = ($lm_pictures > $lm_tags) ? $lm_pictures : $lm_tags;

		//echo '<pre>'; print_r($gallery_model->selectAllPicsSortByYear()); exit();

		$response->setCache(array
		(
			'etag'          => NULL,//md5(serialize($pictures)),
			'last_modified' => $last_modify,
			'max_age'       => 0,
			's_maxage'      => 0,
			'public'        => TRUE,
		));

		if ($response->isNotModified($this->getRequest()))
		{
			return $response;
		}

		$styles =  array
		(
			S_FILE_PATH . 'main.css',
			S_FILE_PATH . 'pirobox/pirobox.css',
		);
		$scripts =  array
		(
			J_FILE_PATH . 'jquery/jquery-1.6.4.min.js',
			J_FILE_PATH . 'pirobox/jquery.pirobox-1.2.2.min.js',
			J_FILE_PATH . 'gallery.js'
		);

		$data = array
		(
			'styles'       => $this->setAssetCss('frontend', $styles),
			'scripts'      => $this->setAssetCss('frontend', $scripts),
			'page'         => $page_model->getPage('index/index'),
			'subtemplates' => array('content' => 'frontend/gallery'),
			'pictures'     => $gallery_model->selectAllPicsSortByYear(),
			'tags'         => $tag_model->selectAllTagsWithClass($gallery_model),
		);
		return $this->render('front_page', $data, $response);
	}

	public function oneTag ($tag)
	{
		if (is_null($tag))
		{
			//TODO: разобраться с языками
			$this->notFound('Страницы не существует');
		}

		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		/*$pictures = $gallery_model->selectPicsByTag($tag);

		if (!$pictures)
		{
			return $this->notFound('Не найдено соответствующего тега или нет изображенией отмеченных тегом.');
		}*/

		$response = new Response();
		$last_modify = $gallery_model->getLastModifyDate();

		$response->setCache(array
		(
			'etag'          => NULL,//md5(serialize($pictures)),
			'last_modified' => $last_modify,
			'max_age'       => 0,//60,
			's_maxage'      => 0,//60,
			'public'        => TRUE,
		));

		if ($response->isNotModified($this->getRequest()))
		{
			return $response;
		}

		$data = array
		(
			'styles'       => array
			(
				S_FILE_PATH . 'main.css',
				S_FILE_PATH . 'pirobox/pirobox.css',
			),
			'scripts'      => array
			(
				J_FILE_PATH . 'jquery/jquery-1.6.4.min.js',
				J_FILE_PATH . 'pirobox/jquery.pirobox-1.2.2.min.js',
				J_FILE_PATH . 'gallery.js'
			),
			'page'         => $page_model->getPage('index/onetag'),
			'subtemplates' => array('content' => 'frontend/gallery_tag'),
			'pictures'     => $gallery_model->selectPicsByTag($tag),
			'tag'          => $tag,
		);
		return $this->render('front_page', $data, $response);
	}

	public function byTag ()
	{
		$page_model    = new Page($this->getDatabase());
		$tag_model     = new Tag($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		$response = new Response();

		$lm_pictures = $gallery_model->getLastModifyDate();
		$lm_tags     = $tag_model->getLastModifyDate();
		$last_modify = ($lm_pictures > $lm_tags) ? $lm_pictures : $lm_tags;

		$response->setCache(array
		(
			'etag'          => NULL,//md5(serialize($pictures)),
			'last_modified' => $last_modify,
			'max_age'       => 0,//60,
			's_maxage'      => 0,//60,
			'public'        => TRUE,
		));

		if ($response->isNotModified($this->getRequest()))
		{
			return $response;
		}

		$data = array
		(
			'styles'       => array
			(
				S_FILE_PATH . 'main.css',
				S_FILE_PATH . 'pirobox/pirobox.css',
			),
			'scripts'      => array
			(
				J_FILE_PATH . 'jquery/jquery-1.6.4.min.js',
				J_FILE_PATH . 'pirobox/jquery.pirobox-1.2.2.min.js',
				J_FILE_PATH . 'gallery.js'
			),
			'page'         => $page_model->getPage('index/bytag'),
			'subtemplates'       => array('content' => 'frontend/gallery_bytag'),
			'tags_with_pictures' => $tag_model->selectAllTagsWithPics($gallery_model),
			'tags'               => $tag_model->selectAllTagsWithClass($gallery_model),
		);
		return $this->render('front_page', $data, $response);
	}

	public function css ()
	{
		$page_model    = new Page($this->getDatabase());

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
			'page'         => $page_model->getPage('index/css'),
			'subtemplates' => array('content' => 'frontend/css'),
		);
		return $this->render('front_page', $data);
	}
}