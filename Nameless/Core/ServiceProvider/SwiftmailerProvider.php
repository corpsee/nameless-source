<?php

namespace Nameless\Core\ServiceProvider;

use Nameless\Core\Container;
use Nameless\Core\Kernel;
use Nameless\Core\ProviderInterface;

class SwiftmailerProvider implements ProviderInterface
{
	/**
	 * @param Container $container
	 */
	public function register (\Pimple $container)
	{
		$container['mailer'] = $container->share(function ($c)
		{
			return new \Swift_Mailer($c['mailer_transport']);
		});

		$container['mailer_transport'] = $container->share(function ($c)
		{
			return new \Swift_Transport_MailTransport($c['mailer_transport_invoker'], $c['mailer_transport_eventdispatcher']);
		});

		$container['mailer_transport_invoker'] = $container->share(function ()
		{
			return new \Swift_Transport_SimpleMailInvoker();
		});

		$container['mailer_transport_eventdispatcher'] = $container->share(function ()
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
