<?php

namespace Nameless\Modules\Auto;

use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Controller;

class BackendController extends Controller
{
	public function before()
	{

		$access = $this->container['user']->getAccessByRoute($this->getAttributes('_route'));

		if (!$access)
		{
			throw new AccessDeniedException('Access Denied!');
		}
	}
}