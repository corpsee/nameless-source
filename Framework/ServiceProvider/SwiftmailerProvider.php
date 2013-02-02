<?php

namespace Framework\ServiceProvider;

use Framework\Container;
use Framework\Kernel;
use Framework\ProviderInterface;

class SwiftmailerProvider implements ProviderInterface
{
	/**
	 * @param Container $container
	 */
	public function register (Container $container)
	{
		$container->mailer = $container->service(function ($c)
		{
			return new \Swift_Mailer($c->mailer_transport);
		});

		$container->mailer_transport = $container->service(function ($c)
		{
			return new \Swift_Transport_MailTransport($c->mailer_transport_invoker, $c->mailer_transport_eventdispatcher);
		});

		$container->mailer_transport_invoker = $container->service(function ()
		{
			return new \Swift_Transport_SimpleMailInvoker();
		});

		$container->mailer_transport_eventdispatcher = $container->service(function ()
		{
			return new \Swift_Events_SimpleEventDispatcher();
		});
	}

	/**
	 * @param Kernel $kernel
	 */
	public function boot (Kernel $kernel)
	{
	}
}
