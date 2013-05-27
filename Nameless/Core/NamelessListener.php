<?php

/**
 * This file is part of the Nameless framework.
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Core;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * NamelessListener class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class NamelessListener implements EventSubscriberInterface
{
	/**
	 * @var SessionInterface
	 */
	protected $session;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @var Benchmark
	 */
	protected $benchmark;

	/**
	 * @param SessionInterface $session
	 * @param Benchmark        $benchmark
	 * @param LoggerInterface  $logger
	 */
	public function __construct (SessionInterface $session, Benchmark $benchmark = NULL, LoggerInterface $logger = NULL)
	{
		$this->session   = $session;
		$this->logger    = $logger;
		$this->benchmark = $benchmark;
	}

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
	 * @param GetResponseEvent $event
	 */
	public function onEarlyKernelRequest (GetResponseEvent $event)
	{
		$request = $event->getRequest();
		$request->setSession($this->session);

		// starts the session if a session cookie already exists in the request...
		if ($request->hasPreviousSession())
		{
			$request->getSession()->start();
		}

		if (!is_null($this->logger))
		{
			$this->logger->info('> ' . $request->getMethod() . ' ' . $request->getRequestUri());
		}
	}

	/**
	 * @param PostResponseEvent $event
	 */
	public function onTerminate (PostResponseEvent $event)
	{
		$response  = $event->getResponse();

		if (!is_null($this->logger))
		{
			$this->logger->info('< ' . $response->getStatusCode());

			if (!is_null($this->benchmark))
			{
				$total = $this->benchmark->getAppStatistic();
				$this->logger->info('= Time: ' . $total['time'] . ', Memory: ' . $total['memory']);
			}
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
			KernelEvents::REQUEST    => array('onEarlyKernelRequest'),
			KernelEvents::TERMINATE  => array('onTerminate'),
		);
	}
}