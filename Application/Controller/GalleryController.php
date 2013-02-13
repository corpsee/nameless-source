<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Tag;
use Framework\Auto;

class GalleryController extends BackendController
{
	public function index ()
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());
		$tag_model     = new Tag($this->getDatabase());

		$data = array
		(
			'styles'       => array
			(
				STYLE_PATH_URL . 'reset.css',
				STYLE_PATH_URL . 'typographic.css'
			),
			'scripts'      => array(),
			'page'         => $page_model->getPage('admin/login'),
			'subtemplates' => array('content' => 'backend/gallery/gallery_list'),
			'pictures'     => $gallery_model->selectAllPicsWithTags($tag_model),
			'links'        => array
			(
				'add'       => $this->container->user->getAccess('gallery_add'),
				'delete'    => $this->container->user->getAccess('gallery_delete'),
				'edit'      => $this->container->user->getAccess('gallery_edit'),
				'editimage' => $this->container->user->getAccess('gallery_editimage'),
				'crop'      => $this->container->user->getAccess('gallery_crop'),
			)
		);
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
			//print_r($this->getValidation('GalleryForm')); exit();
			return $this->getValidation('GalleryForm');
		}

		if ($this->isMethod('POST'))
		{
			// валидация
			if ($this->container->validator->validate('GalleryForm'))
			{
				return $this->forward('error', array('code' => 4));
			}

			$file = $this->getFiles('file');
			// TODO: FILES? +некрасивый код с типами
			$filename       = explode('.', $file->getClientOriginalName());
			$filename_clear = standardize($filename[0]);
			$fileinfo       = getimagesize($file->getPathName());

			if ($fileinfo['mime'] == 'image/jpeg' || 'image/png' || 'image/gif')
			{
				$gallery_model->addPicture
				(
					$tag_model,
					$this->container->request->request->get('title'),
					$file->getPathName(),
					$filename_clear,
					$this->container->request->request->get('description'),
					$this->container->request->request->get('tags'),
					$this->container->request->request->get('create_date'),
					$fileinfo['mime']
				);
				return $this->redirect('/admin/gallery/crop/' . $filename_clear);
			}
			else
			{
				return $this->forward('error', array('code' => 3));
			}
		}

		$data = array
		(
			'styles'       => array
			(
				STYLE_PATH_URL . 'reset.css',
				STYLE_PATH_URL . 'typographic.css',
				STYLE_PATH_URL . 'jquery-ui/jquery-ui-1.8.16.custom.css'
			),
			'scripts'      => array
			(
				SCRIPT_PATH_URL . 'jquery/jquery-1.6.4.min.js',
				SCRIPT_PATH_URL . 'jquery-ui/jquery-ui-1.8.16.custom.min.js',
				SCRIPT_PATH_URL . 'validation.js',
				SCRIPT_PATH_URL . 'datepicker.js',
			),
			'page'         => $page_model->getPage('gallery/add'),
			'subtemplates' => array('content' => 'backend/gallery/gallery_add'),
			'tags'         => $tag_model->selectAllTagsInString(),
		);
		return $this->render('back_page', $data);
	}

	public function crop ($image)
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		if ($this->isMethod('POST'))
		{
			//print_r($this->getPost()); exit();
			$gallery_model->cropPicture
			(
				$this->getPost('w'),
				$this->getPost('h'),
				$this->getPost('x'),
				$this->getPost('y'),
				$image
			);
			return $this->redirect('/admin/gallery/result/' . $image);
		}

		$source_img = imagecreatefromjpeg(FILE_PATH . 'pictures/x/' . $image . '.jpg');

		$data = array
		(
			'styles'       => array
			(
				STYLE_PATH_URL . 'reset.css',
				STYLE_PATH_URL . 'typographic.css'
			),
			'scripts'      => array
			(
				SCRIPT_PATH_URL . 'jquery/jquery-1.6.4.min.js',
				SCRIPT_PATH_URL . 'jcrop/jquery.jcrop-0.9.9.min.js',
				SCRIPT_PATH_URL . 'jcrop.js'
			),
			'page'         => $page_model->getPage('gallery/crop'),
			'subtemplates' => array('content' => 'backend/gallery/gallery_crop'),
			'image'        => array
			(
				'image' => $image,
				'width'    => imagesx($source_img),
				'height'   => imagesy($source_img)
			)
		);
		return $this->render('back_page', $data);
	}

	public function result ($image)
	{
		$page_model = new Page($this->getDatabase());

		$data = array
		(
			'styles'       => array
			(
				STYLE_PATH_URL . 'reset.css',
				STYLE_PATH_URL . 'typographic.css'
			),
			'scripts'      => array
			(
				SCRIPT_PATH_URL . 'jquery/jquery-1.6.4.min.js',
			),
			'page'         => $page_model->getPage('gallery/result'),
			'subtemplates' => array('content' => 'backend/gallery/gallery_result'),
			'image'        => array('min'  => $image . '-min', 'gray' => $image . '-gray')
		);
		return $this->render('back_page', $data);
	}

	public function edit ($id)
	{
		$page_model    = new Page($this->getDatabase());
		$tag_model     = new Tag($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		// ajax-валидация (клиентская)
		if ($this->isAjax())
		{
			return $this->getValidation('GalleryForm');
		}

		if ($this->isMethod('POST'))
		{
			//echo '<pre>'; print_r($_POST); exit();
			if ($this->container->validator->validate('GalleryForm'))
			{
				return $this->forward('error', array('code' => 4));
			}

			$gallery_model->UpdatePicture
			(
				$tag_model,
				$id,
				$this->getPost('title'),
				$this->getPost('description'),
				$this->getPost('tags'),
				$this->getPost('create_date')
			);
			return $this->forward('gallery');
		}

		$data = array
		(
			'styles'       => array
			(
				STYLE_PATH_URL . 'reset.css',
				STYLE_PATH_URL . 'typographic.css',
				STYLE_PATH_URL . 'jquery-ui/jquery-ui-1.8.16.custom.css'
			),
			'scripts'      => array
			(
				SCRIPT_PATH_URL . 'jquery/jquery-1.6.4.min.js',
				SCRIPT_PATH_URL . 'jquery-ui/jquery-ui-1.8.16.custom.min.js',
				SCRIPT_PATH_URL . 'validation.js',
				SCRIPT_PATH_URL . 'datepicker.js'
			),
			'page'         => $page_model->getPage('gallery/edit'),
			'subtemplates' => array('content' => 'backend/gallery/gallery_edit'),
			'tags'         => $tag_model->selectAllTagsInString()
		);

		$picture = $gallery_model->selectPicByIDWithTagsInString($id, $tag_model);
		$image = FILE_PATH_URL . 'pictures/x/' . $picture['image'] . '.jpg';

		$data['values'] = array
		(
			'title'       => $picture['title'],
			'description' => $picture['description'],
			'tags'        => $picture['tags'],
			'create_date' => $picture['create_date'],
			'filename'    => $image,
		);
		$data['image']  = array('id' => $id);

		return $this->render('back_page', $data);
	}

	public function editImage ($id)
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		if ($this->isMethod('POST'))
		{
			$file = $this->getFiles('file');

			$filename       = explode('.', $file->getClientOriginalName());
			$filename_clear = standardize($filename[0]);
			$fileinfo       = getimagesize($file->getPathName());

			if ($fileinfo['mime'] == 'image/jpeg' || 'image/png' || 'image/gif')
			{
				$gallery_model->updatePictureImage
				(
					$id,
					$file->getPathName(),
					$filename_clear,
					$fileinfo['mime']
				);
				return $this->redirect('/admin/gallery/crop/' . $filename_clear);
			}
			else
			{
				return $this->forward('error', array('code' => 3));
			}
		}

		$data = array
		(
			'styles'       => array
			(
				STYLE_PATH_URL . 'reset.css',
				STYLE_PATH_URL . 'typographic.css',
			),
			'scripts'      => array
			(
				SCRIPT_PATH_URL . 'jquery/jquery-1.6.4.min.js',
			),
			'page'         => $page_model->getPage('gallery/editimage'),
			'subtemplates' => array('content' => 'backend/gallery/gallery_editimage'),
			'image'        => array('id' => $id),
		);
		return $this->render('back_page', $data);
	}

	public function delete ($id)
	{
		$gallery_model = new Gallery($this->getDatabase());

		$gallery_model->deletePicture($id);
		return $this->forward('gallery');
	}
}
