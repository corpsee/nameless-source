<?php

namespace Application\Controller;

use Application\Model\Page;
use Framework\Auto\Auto;
use Framework\Auto\User;
use Framework\Auto\Provider\FileUserProvider;

class AdminController extends BackendController
{
	public function login ()
	{
		if (in_array('ROLE_REGISTERED', $this->container->user->getUserGroups()))
		{
			return $this->redirect('/admin/gallery');
		}

		$page_model = new Page($this->getDatabase());
		$auto = new Auto(new FileUserProvider($this->container->users));

		/*if ($this->getRequest()->cookies->has(User::COOKIE_AUTOLOGIN) && !$auto->autoAuthenticate($this->getCookies(User::COOKIE_AUTOLOGIN)))
		{
			$this->container->user->autoLogin($auto);
			echo 1; exit;
			return $this->redirect('/admin/gallery');
		}*/

		if ($this->isMethod('POST'))
		{
			// аутентификация
			$authenticate = $auto->authenticate($this->getPost('login'), $this->getPost('password'));

			if ($authenticate === 0)
			{
				//$response = new RedirectResponse('/admin/gallery');
				//$response = $this->container->user->login($auto, $response, 3600*24*30);
				//return $response;
				//echo 1; exit;
				$this->container->user->login($auto);
				return $this->redirect('/admin/gallery');
			}
			elseif ($authenticate === 1)
			{
				return $this->forward('error', array('code' => 1));
			}
			else
			{
				return $this->forward('error', array('code' => 2));
			}
		}

		$data = array
		(
			'styles'       => array
			(
				STYLE_PATH_URL . 'reset.css',
				STYLE_PATH_URL . 'typographic.css'
			),
			'scripts'      => array(),
			'page'         => $page_model->getPage('admin/login'),
			'subtemplates' => array('content' => 'backend/login'),
			'action'       => '/admin/login',
		);

		return $this->render('back_page_minus', $data);
	}

	public function logout ()
	{
		$this->container->user->logout();
		return $this->redirect('/admin');
	}
}
