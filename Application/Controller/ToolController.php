<?php

namespace Application\Controller;

use Framework\Controller;
use Application\Model\Page;

class ToolController extends Controller
{
	public function index ()
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
				SCRIPT_PATH_URL . 'jquery/jquery-1.8.3.min.js',
			),
			'page'         => $page_model->getPage('admin/login'),
			'subtemplates' => array('content' => 'frontend' . DS . 'tool_index'),
		);

		return $this->render('back_page_minus', $data);
	}
}