<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Tag;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends FrontendController
{
	private function getScripts()
	{
		return array
		(
			FILE_PATH_URL . 'j/' . 'jquery/jquery-1.6.4.min.js',
			FILE_PATH_URL . 'j/' . 'pirobox/jquery.pirobox-1.2.2.min.js',
			FILE_PATH_URL . 'j/' . 'gallery.js'
		);
	}

	private function getStyles()
	{
		return array
		(
			FILE_PATH_URL . 's/' . 'main.css',
			FILE_PATH_URL . 's/' . 'pirobox/pirobox.css',
		);
	}

	public function index ()
	{
		$message = \Swift_Message::newInstance()->setSubject('[YourSite] Feedback')->setFrom(array('noreply@yoursite.com'))->setTo(array('feedback@yoursite.com'))->setBody('message');
		return $this->container->mailer->send($message);

		//mail('caffeinated@example.com', 'My Subject', 'message');

		//$this->notFound();

		/*$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());
		$tag_model     = new Tag($this->getDatabase());

		//TODO: учитывать css/js
		$lm_pictures = $gallery_model->getLastModifyDate();
		$lm_tags     = $tag_model->getLastModifyDate();
		$last_modify = ($lm_pictures > $lm_tags) ? $lm_pictures : $lm_tags;

		$response = new Response();
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

		$data = array
		(
			'styles'       => $this->setAsset('frontend.min', $this->getStyles(), 'css'),
			'scripts'      => $this->setAsset('frontend.min', $this->getScripts(), 'js'),
			'page'         => $page_model->getPage('index/index'),
			'subtemplates' => array('content' => 'frontend/gallery'),
			'pictures'     => $gallery_model->selectAllPicsSortByYear(),
			'tags'         => $tag_model->selectAllTagsWithClass($gallery_model),
		);
		return $this->render('front_page', $data, $response);*/
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

		$last_modify = $gallery_model->getLastModifyDate();

		$response = new Response();
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
			'styles'       => $this->setAsset('frontend.min', $this->getStyles(), 'css'),
			'scripts'      => $this->setAsset('frontend.min', $this->getScripts(), 'js'),
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

		$lm_pictures = $gallery_model->getLastModifyDate();
		$lm_tags     = $tag_model->getLastModifyDate();
		$last_modify = ($lm_pictures > $lm_tags) ? $lm_pictures : $lm_tags;

		$response = new Response();
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
			'styles'       => $this->setAsset('frontend.min', $this->getStyles(), 'css'),
			'scripts'      => $this->setAsset('frontend.min', $this->getScripts(), 'js'),
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