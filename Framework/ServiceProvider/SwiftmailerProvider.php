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
			return new \Swift_Mailer($c->mailer_spooltransport);
		});

		$container->mailer_spooltransport = $container->service(function ($c)
		{
			return new \Swift_SpoolTransport($c->mailer_spool);
		});

		$container->mailer_spool = $container->service(function ()
		{
			return new \Swift_MemorySpool();
		});

		$container->mailer_transport = $container->service(function ($c)
		{
			$transport = new \Swift_Transport_EsmtpTransport
			(
				$c->mailer_transport_buffer,
				array($c->mailer_transport_authhandler),
				$c->mailer_transport_eventdispatcher
			);

			$options = $c->mailer_settings = array_replace
			(
				array
				(
					'host' => 'localhost',
					'port' => 25,
					'username' => '',
					'password' => '',
					'encryption' => NULL,
					'auth_mode' => NULL,
				), $c->mailer_settings
			);

			$transport->setHost($options['host']);
			$transport->setPort($options['port']);
			$transport->setEncryption($options['encryption']);
			$transport->setUsername($options['username']);
			$transport->setPassword($options['password']);
			$transport->setAuthMode($options['auth_mode']);

			return $transport;
		});

		$container->mailer_transport_buffer = $container->service(function ()
		{
			return new \Swift_Transport_StreamBuffer(new \Swift_StreamFilters_StringReplacementFilterFactory());
		});

		$container->mailer_transport_authhandler = $container->service(function ()
		{
			return new \Swift_Transport_Esmtp_AuthHandler
			(
				array
				(
					new \Swift_Transport_Esmtp_Auth_CramMd5Authenticator(),
					new \Swift_Transport_Esmtp_Auth_LoginAuthenticator(),
					new \Swift_Transport_Esmtp_Auth_PlainAuthenticator(),
				)
			);
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
