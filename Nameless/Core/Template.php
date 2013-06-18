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

use Symfony\Component\HttpFoundation\Response;

//TODO: подумать над фильтрами (анти-xss/экранирование)
/**
 * Template class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Template
{
	/**
	 * @var Response
	 */
	protected $response;

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var array
	 */
	protected static $global_data = array();

	/**
	 * @var string
	 */
	protected $template_path;

	/**
	 * @var string
	 */
	protected $template;

	/**
	 * @var string
	 */
	protected $template_fullpath;

	/**
	 * @var string
	 */
	protected $template_extension;

	/**
	 * @param string   $template_path
	 * @param string   $template_extension
	 * @param array    $data
	 * @param string   $template
	 * @param Response $response
	 */
	public function __construct ($template_path, $template_extension = 'tpl', array $data = array(), $template = '', Response $response = NULL)
	{
		$this->template_path      = $template_path;
		$this->template_extension = $template_extension;
		$this->data               = $data;
		$this->template           = $template;
		$this->response           = $response;
	}

	/**
	 * @param string|array $data_name
	 * @param mixed        $data_value
	 *
	 * @return Template
	 * @throws \InvalidArgumentException
	 */
	public function setData ($data_name, $data_value = NULL)
	{
		if (is_array($data_name) && is_null($data_value))
		{
			$this->data = $data_name;
		}
		elseif ($data_name)
		{
			$this->data[$data_name] = $data_value;
		}
		else
		{
			throw new \InvalidArgumentException('Invalid argument for set template data');
		}
		return $this;
	}

	/**
	 * @param mixed $data_name
	 * @param mixed $data_default
	 *
	 * @return mixed
	 * @throws \OutOfBoundsException
	 */
	public function getData ($data_name = NULL, $data_default = NULL)
	{
		if (is_null($data_name))
		{
			return $this->data;
		}
		elseif (isset($this->data[$data_name]))
		{
			return $this->data[$data_name];
		}
		elseif (is_null($data_default))
		{
			throw new \OutOfBoundsException('Value doesn`t exist in template data');
		}
		return $data_default;
	}

	/**
	 * @param mixed $data_name
	 * @param mixed $data_value
	 *
	 * @return Template
	 */
	public function bindData ($data_name, &$data_value)
	{
		$this->data[$data_name] = $data_value;
		return $this;
	}

	/**
	 * @param string|array $data_name
	 * @param mixed        $data_value
	 *
	 * @throws \InvalidArgumentException
	 */
	public static function setGloabalData ($data_name, $data_value = NULL)
	{
		if (is_array($data_name) && is_null($data_value))
		{
			self::$global_data = $data_name;
		}
		elseif ($data_name)
		{
			self::$global_data[$data_name] = $data_value;
		}
		else
		{
			throw new \InvalidArgumentException('Invalid argument for set template global data');
		}
	}

	/**
	 * @param mixed $data_name
	 * @param mixed $data_default
	 *
	 * @return mixed
	 * @throws \OutOfBoundsException
	 */
	public static function getGlobalData ($data_name = NULL, $data_default = NULL)
	{
		if (is_null($data_name))
		{
			return self::$global_data;
		}
		elseif (isset(self::$global_data[$data_name]))
		{
			return self::$global_data[$data_name];
		}
		elseif (is_null($data_default))
		{
			throw new \OutOfBoundsException('Value doesn`t exist in template data');
		}
		return $data_default;
	}

	/**
	 * @param mixed $data_name
	 * @param mixed $data_value
	 */
	public static function bindGlobalData ($data_name, &$data_value)
	{
		self::$global_data[$data_name] = $data_value;
	}

	/**
	 * @param string $template_path
	 *
	 * @return Template
	 */
	public function setTemplatePath ($template_path)
	{
		$this->template_path = $template_path;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTemplatePath ()
	{
		return $this->template_path;
	}

	/**
	 * @param Response $response
	 *
	 * @return Template
	 */
	public function setResponse (Response $response)
	{
		$this->response = $response;
		return $this;
	}

	/**
	 * @return Response|NULL
	 */
	public function getResponse ()
	{
		return $this->response;
	}

	/**
	 * @param string $template
	 *
	 * @return Template
	 */
	public function setTemplate ($template)
	{
		$this->template = $template;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTemplate ()
	{
		return $this->template;
	}

	/**
	 * @param string $template_extension
	 *
	 * @return Template
	 */
	public function setTemplateExtension ($template_extension)
	{
		$this->template_extension = $template_extension;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTemplateExtension ()
	{
		return $this->template_extension;
	}

	/**
	 * @return string
	 * @throws \RuntimeException
	 * @throws \Exception
	 */
	protected function renderTemplate ()
	{
		extract($this->data, EXTR_REFS);

		$this->template_fullpath = $this->template_path . $this->template . '.' . $this->template_extension;
		if (!file_exists($this->template_fullpath))
		{
			throw new \RuntimeException('Template file: ' . $this->template_fullpath . ' doesn`t exist.');
		}

		ob_start();

		try
		{
			include $this->template_fullpath;
		}
		catch (\Exception $exception)
		{
			ob_end_clean();
			throw $exception;
		}
		return ob_get_clean();
	}

	public function subtemplate ($subtamplate)
	{
		$subtamplate_instance = new static($this->template_path, $this->template_extension, $this->data, $subtamplate);
		return $subtamplate_instance->renderTemplate();
	}

	/**
	 * @param string   $template
	 * @param array    $data
	 * @param Response $response
	 *
	 * @return Response
	 */
	public function render($template = '', array $data = array(), Response $response = NULL)
	{
		if ($data)
		{
			$this->data = $data;
		}

		if ($template)
		{
			$this->template = $template;
		}

		if (is_null($response) && is_null($this->response))
		{
			$this->response = new Response();
		}
		elseif ($response)
		{
			$this->response = $response;
		}

		$this->response->setContent($this->renderTemplate());
		return $this->response;
	}
}