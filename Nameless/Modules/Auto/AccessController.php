<?php

/**
 * This file is part of the Nameless framework.
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Auto;

use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Controller;

class BackendController extends Controller
{
	public function before()
	{

		$access = $this->container['auto']['user']->getAccessByRoute($this->getAttributes('_route'));

		if (!$access)
		{
			throw new AccessDeniedException('Access Denied!');
		}
	}
}