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
		$module_path = __DIR__ . DS;
		$module_name = basename($module_path);

		$config      = include_once($module_path . 'configs' . DS . 'configuration.php');

		foreach ($config as $config_option => $config_value)
		{
			if ($config_value && !is_array($config_value) && strtolower($module_name) !== $config_option)
			{
				throw new \RuntimeException('Invalid module configuration array: ' . $module_name);
			}

			foreach ($config_value as $module_option => $module_value)
			{
				$full_module_option = $config_option . '.' . $module_option;
				if (!isset($this->container[$full_module_option]))
				{
					$this->container[$full_module_option] = $module_value;
				}
			}
		}
	}

	public function register ()
	{
		$this->configurationInit();
	}

	abstract public function boot ();
}
