<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Core;

/**
 * ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
abstract class ModuleProvider
{
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
		$app_config_file = CONFIG_PATH . strtolower(static::MODULE_NAME) . '_configuration.php';
		if (file_exists($app_config_file))
		{
			$app_config = include_once($app_config_file);
		}

		$default_config = include_once(ROOT_PATH . 'Nameless' . DS . 'Modules' . DS . static::MODULE_NAME . DS . 'Configs' . DS . 'configuration.php');
		$config         = array_merge($default_config, $app_config);

		foreach ($config as $option => $value)
		{
			$this->container[$option] = $value;
		}
	}

	public function register ()
	{
		$this->configurationInit();
	}

	abstract public function boot ();
}
