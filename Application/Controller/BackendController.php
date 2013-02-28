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
		//return $this->container->validator->validate($form);
		if ($msg = $this->container->validator->validate($form))
		{
			//print_r($msg); exit;
			return new Response
			(
				json_encode(array('status' => 'error', 'msg' => $msg)),
				200,
				array('Content-Type' => 'application/json')
			);
		}
		else
		{
			return new Response
			(
				json_encode(array('status' => 'success')),
				200,
				array('Content-Type' => 'application/json')
			);
		}
	}
}