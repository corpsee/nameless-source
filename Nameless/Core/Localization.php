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
 * Localization class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Localization
{
	/**
	 * @var array
	 */
	protected $files = array();

	/**
	 * @var array
	 */
	protected $lines = array();

	/**
	 * @var string
	 */
	protected $default_language;

	/**
	 * @param string $default_language
	 */
	public function __construct($default_language = 'en')
	{
		$this->default_language = $default_language;
	}

	//TODO: добавить обработку массивов в $file
	//TODO: добавить обработку абсолютных путей в $file
	/**
	 * @param sting   $file
	 * @param string  $module
	 * @param string  $language
	 * @param boolean $overwrite
	 *
	 * @throws \RuntimeException
	 *
	 * @return boolean
	 */
	public function load ($file, $module = 'application', $language = NULL, $overwrite = FALSE)
	{
		if (!$overwrite && isset($this->files[$module][$language][$file]))
		{
			return $this;
		}

		$module = strtolower($module);
		switch ($module)
		{
			case 'core':
				$file_path         = NAMELESS_PATH . ucfirst($module) . DS . 'Localization' . DS . $language . DS . $file . '.php';
				$default_file_path = NAMELESS_PATH . ucfirst($module) . DS . 'Localization' . DS . $this->default_language . DS . $file . '.php';
				break;
			case 'application':
				$file_path         = APPLICATION_PATH . 'Localization' . DS . $language . DS . $file . '.php';
				$default_file_path = APPLICATION_PATH . 'Localization' . DS . $this->default_language . DS . $file . '.php';
				break;
			default:
				$file_path         = NAMELESS_PATH . 'Modules' . DS . ucfirst($module) . DS . 'Localization' . DS . $language . DS . $file . '.php';
				$default_file_path = NAMELESS_PATH . 'Modules' . DS . ucfirst($module) . DS . 'Localization' . DS . $this->default_language . DS . $file . '.php';
		}

		if (file_exists($file_path))
		{
			if (!isset($this->lines[$language]))
			{
				$this->lines[$language] = array();
			}

			$lines = include_once $file_path;
			$this->lines[$language] = array_merge($this->lines[$language], $lines);

			return $this;
		}
		elseif (file_exists($default_file_path))
		{
			if (!isset($this->lines[$this->default_language]))
			{
				$this->lines[$this->default_language] = array();
			}

			$lines = include_once $default_file_path;
			$this->lines[$this->default_language] = array_merge($this->lines[$this->default_language], $lines);

			return $this;
		}
		else
		{
			throw new \RuntimeException('Don`t find language file');
		}
	}

	/**
	 * @param string $line_name
	 * @param string $language
	 * @param array  $params
	 *
	 * @return string
	 *
	 * @throws \RuntimeException
	 */
	public function get ($line_name, $language = NULL, array $params = array())
	{
		$params_temp = array();
		foreach ($params as $param_name => $param)
		{
			$params_temp[':' . $param_name . ':'] = $param;
		}
		unset($param);

		if (isset($this->lines[$language][$line_name]))
		{
			return strtr($this->lines[$language][$line_name], $params_temp);
		}
		elseif (isset($this->lines[$this->default_language][$line_name]))
		{
			return strtr($this->lines[$this->default_language][$line_name], $params_temp);
		}
		else
		{
			throw new \RuntimeException('Don`t find language line');
		}
	}
}