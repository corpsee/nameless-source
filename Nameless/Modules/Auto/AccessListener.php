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

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class NamelessListener implements EventSubscriberInterface
{
	/**
	 * @param FilterControllerEvent $event
	 */
	public function onKernelController (FilterControllerEvent $event)
	{
		$controller = $event->getController();

		if (!is_array($controller)) { return; }

		if ($controller[0] instanceof ControllerInterface && method_exists($controller[0], 'before'))
		{
			$controller[0]->before();
		}
	}

	/**
	 * @return array
	 */
	static public function getSubscribedEvents()
	{
		return array
		(
			KernelEvents::CONTROLLER => array('onKernelController'),
		);
	}
}