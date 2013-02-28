<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Tag;
use Framework\Auto;

class TagController extends BackendController
{
	public function index ()
	{
		$page_model    = new Page($this->getDatabase());
		$tag_model     = new Tag($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		$data = array
		(
			'styles'       => array
			(
				STYLE_PATH_URL . 'reset.css',
				STYLE_PATH_URL . 'typographic.css'
			),
			'scripts'      => array(),
			'page'         => $page_model->getPage('tag/index'),
			'subtemplates' => array('content' => 'backend' . DS . 'tags' . DS . 'tags_list'),
			'tags'         => $tag_model->selectAllTagsWithPicInString($gallery_model),
			'links'        => array
			(
				'add'       => $this->container->user->getAccessByRoute('tag_add'),
				'delete'    => $this->container->user->getAccessByRoute('tag_delete'),
				'edit'      => $this->container->user->getAccessByRoute('tag_edit'),
			)
		);
		//echo '<pre>'; var_dump($data); echo '</pre>';
		return $this->render('back_page', $data);
	}

	public function add ()
	{
		$page_model    = new Page($this->getDatabase());
		$tag_model     = new Tag($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		// ajax-валидация (клиентская)
		if ($this->isAjax())
		{
			return $this->getValidation('TagForm');
		}

		if ($this->isMethod('POST'))
		{
			if ($this->container->validator->validate('TagForm'))
			{
				return $this->forward('error', array('code' => 4));
			}

			if (!$tag_model->addTag($gallery_model, $this->getPost('tag'), $this->getPost('pictures')))
			{
				return $this->forward('error', array('code' => 6));
			}
			return $this->forward('tag');
		}

		$data = array
		(
			'styles'       => array
			(
				STYLE_PATH_URL . 'reset.css',
				STYLE_PATH_URL . 'typographic.css',
				STYLE_PATH_URL . 'chosen/chosen.css'
			),
			'scripts'      => array
			(
				SCRIPT_PATH_URL . 'jquery/jquery-1.6.4.min.js',
				SCRIPT_PATH_URL . 'chosen/chosen.jquery.min.js',
				SCRIPT_PATH_URL . 'validation.js',
				SCRIPT_PATH_URL . 'select.js'
			),
			'page'         => $page_model->getPage('tag/add'),
			'subtemplates' => array('content' => 'backend' . DS . 'tags' . DS . 'tags_add'),
			'pictures'     => $gallery_model->selectAllPics()
		);
		return $this->render('back_page', $data);
	}

	/**
	 * Edit tag form action
	 * @param integer $id
	 */
	public function edit ($id)
	{
		$page_model    = new Page($this->getDatabase());
		$tag_model     = new Tag($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		// ajax-валидация (клиентская)
		if ($this->isAjax())
		{
			return $this->getValidation('TagForm'); exit();
		}

		if ($this->isMethod('post'))
		{
			if ($this->container->validator->validate('TagForm'))
			{
				return $this->forward('error', array('code' => 4));
			}

			$tag_model->UpdateTag
			(
				$gallery_model,
				(int)$id,
				$this->getPost('tag'),
				$this->getPost('pictures')
			);
			return $this->forward('tag');
		}

		$tag      = $tag_model->selectTagByID($id);
		$pictures = $gallery_model->selectPicsByTag($tag['tag']);

		$data = array
		(
			'styles'       => array
			(
				STYLE_PATH_URL . 'reset.css',
				STYLE_PATH_URL . 'typographic.css',
				STYLE_PATH_URL . 'chosen/chosen.css'
			),
			'scripts'      => array
			(
				SCRIPT_PATH_URL . 'jquery/jquery-1.6.4.min.js',
				SCRIPT_PATH_URL . 'chosen/chosen.jquery.min.js',
				SCRIPT_PATH_URL . 'validation.js',
				SCRIPT_PATH_URL . 'select.js'
			),
			'page'         => $page_model->getPage('tag/edit'),
			'subtemplates' => array('content' => 'backend' . DS . 'tags' . DS . 'tags_edit'),
			'values'       => array
			(
				'tag'      => $tag['tag'],
				'pictures' => $pictures
			),
			'pictures'     => $gallery_model->selectAllPics(),
		);
		return $this->render('back_page', $data);
	}

	public function delete ($id)
	{
		$tag_model     = new Tag($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		$tag_model->deleteTag($gallery_model, $id);
		return $this->forward('tag');
	}
}
