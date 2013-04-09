<?php

/*
 * This file is part of the Framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Framework;

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
class FrameworkListener implements EventSubscriberInterface
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
	 * @param SessionInterface $session
	 * @param LoggerInterface|null $logger
	 */
	public function __construct (SessionInterface $session, LoggerInterface $logger = NULL)
	{
		$this->session = $session;
		$this->logger  = $logger;
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
			$this->logger->info('> '.$request->getMethod().' '.$request->getRequestUri());
		}
	}

	/**
	 * @param PostResponseEvent $event
	 */
	public function onTerminate (PostResponseEvent $event)
	{
		$response = $event->getResponse();

		if (!is_null($this->logger))
		{
			$this->logger->info('< '.$response->getStatusCode());
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