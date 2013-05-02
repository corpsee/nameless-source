<?php

/**
 * This file is part of the Nameless framework.
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Logger;

use Nameless\Core\ModuleProvider as BaseModuleProvider;
use Monolog\Handler\StreamHandler;

class ModuleProvider extends BaseModuleProvider
{
	const MODULE_NAME = 'Logger';

	public function register ()
	{
		parent::register();

		$this->container['logger'] = $this->container->share(function ($c)
		{
			$logger = new Logger($c['logger_name']);
			$logger->pushHandler($c-['logger_handler']);
			return $logger;
		});

		$this->container['logger_handler'] = $this->container->share(function ($c)
		{
			return new StreamHandler($c['log_file'], $c['log_level']);
		});

		$this->container['log_level'] = function ()
		{
			return Logger::DEBUG;
		};

		$this->container['logger_name'] = 'application';
		$this->container['log_file'] = $this->container['log_path'] . $this->container['logger_name'] . '.log';
	}

	public function boot () {}
}
