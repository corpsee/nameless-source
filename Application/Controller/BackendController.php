<?php

namespace Application\Controller;

use Symfony\Component\HttpFoundation\Response;
use Framework\Controller;
use Framework\Auto\AccessDeniedException;

class BackendController extends Controller
{
	public function before()
	{

		$access = $this->container->user->getAccessByRoute($this->getAttributes('_route'));
		//echo var_dump($access);
		if (!$access)
		{
			throw new AccessDeniedException('Access Denied!');
		}
	}

	protected function getValidation ($form)
	{
		if ($msg = $this->container->validator->validate($form))
		{
			$response = array('status' => 'error', 'msg' => $msg);
		}
		else
		{
			$response = array('status' => 'success');
		}

		return new Response(json_encode($response), 200, array('Content-Type' => 'application/json'));
	}
}