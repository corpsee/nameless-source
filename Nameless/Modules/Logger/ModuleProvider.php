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

/**
 * Logger ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
	const MODULE_NAME = 'Logger';

	public function register ()
	{
		parent::register();

		//TODO: вызывать исключение, если не заданы необходимые настройки (['logger']['name'] например)
		$this->container['logger'] = array
		(
			'logger'  => $this->container->share(function ($c)
			{
				$logger = new Logger($c['logger']['name']);
				$logger->pushHandler($c['logger']['handler']);
				return $logger;
			}),
			'handler' => $this->container->share(function ($c)
			{
				return new StreamHandler($c['logger']['file'], $c['logger']['level']);
			}),
			'level'   => function ($c)
			{
				if ($c['environment'] == 'production')
				{
					return Logger::ERROR;
				}
				else
				{
					return Logger::DEBUG;
				}
			},
			'file'    => $this->container['logger']['path'] . $this->container['logger']['name'] . '.log',
		);
	}

	public function boot () {}
}
