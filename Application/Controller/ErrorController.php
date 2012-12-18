<?php

namespace Application\Controller;

use Application\Model\Page;
use Framework\Auto\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorController extends BackendController
{
	public function errorAdmin ($code = NULL)
	{
		$page_model = new Page($this->getDatabase());

		$data = array
		(
			'styles'       => array
			(
				S_FILE_PATH . 'reset.css',
				S_FILE_PATH . 'typographic.css'
			),
			'scripts'      => array(),
			'page'         => $page_model->getPage('admin/error'),
			'subtemplates' => array('content' => 'backend/error'),
		);

		switch ($code)
		{
			case 1:
				$data['error'] = 'Неправильный логин!';
				break;
			case 2:
				$data['error'] = 'Неправильный пароль!';
				break;
			case 3:
				$data['error'] = 'Неверный тип графического файла!';
				break;
			case 4:
				$data['error'] = 'Ошибка в введенных данных!';
				break;
			case 6:
				$data['error'] = 'Такая метка уже существует!';
				break;
			default:
				$data['error'] = 'Ошибка!';
		}

		return $this->render('back_page_minus', $data);
	}

	public function errorServer ($code)
	{
		//print_r($code); exit;
		switch ((int)$code)
		{
			case 403:
				throw new AccessDeniedException('Access denied!');
				break;
			case 404:
				return $this->notFound('Not found');
				break;
			default:
				throw new HttpException(500, 'Server error!');
		}
	}
}
