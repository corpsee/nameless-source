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
				S_FILE_PATH . 'reset.css',
				S_FILE_PATH . 'typographic.css'
			),
			'scripts'      => array
			(
				J_FILE_PATH . 'jquery/jquery-1.8.3.min.js',
			),
			'page'         => $page_model->getPage('admin/login'),
			'subtemplates' => array('content' => 'frontend/tool_index'),
		);

		return $this->render('back_page_minus', $data);
	}
}