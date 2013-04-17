<?php

/*
 * This file is part of the Nameless framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Nameless\Modules\Auto;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * FrameworkListener class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
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