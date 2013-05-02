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

abstract class ModuleProvider
{
	const MODULE_NAME = '';

	/**
	 * @var \Pimple
	 */
	protected $container;

	/**
	 * @param \Pimple $container
	 */
	public function __construct(\Pimple $container)
	{
		$this->container = $container;
	}

	protected function configurationInit ()
	{
		$app_config      = array();
		$app_config_file = CONFIG_PATH . static::MODULE_NAME . '_configuration.php';
		if (file_exists($app_config_file))
		{
			$app_config = include_once($app_config_file);
		}

		$default_config = include_once(ROOT_PATH . 'Nameless' . DS . 'Modules' . DS . static::MODULE_NAME . DS . 'Configs' . DS . 'configuration.php');
		$config         = array_merge($default_config, $app_config);

		foreach ($config as $option => $value)
		{
			$this->container[strtolower(static::MODULE_NAME)][$option] = $value;
		}
	}

	public function register ()
	{
		$this->configurationInit();
	}

	abstract public function boot ();
}
