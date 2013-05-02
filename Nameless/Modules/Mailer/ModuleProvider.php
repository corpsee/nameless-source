<?php

namespace Nameless\Modules\Mailer;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

class ModuleProvider extends BaseModuleProvider
{
	const MODULE_NAME = 'Mailer';

	public function register ()
	{
		parent::register();

		$this->container['mailer'] = $this->container->share(function ($c)
		{
			return new \Swift_Mailer($c['mailer_transport']);
		});

		$this->container['mailer_transport'] = $this->container->share(function ($c)
		{
			return new \Swift_Transport_MailTransport($c['mailer_transport_invoker'], $c['mailer_transport_eventdispatcher']);
		});

		$this->container['mailer_transport_invoker'] = $this->container->share(function ()
		{
			return new \Swift_Transport_SimpleMailInvoker();
		});

		$this->container['mailer_transport_eventdispatcher'] = $this->container->share(function ()
		{
			return new \Swift_Events_SimpleEventDispatcher();
		});
	}

	public function boot () {}
}
