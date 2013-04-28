<?php

/**
 * This file is part of the Nameless framework.
 *
 * @package    Nameless
 * @author     Corpsee (poisoncorpsee@gmail.com)
 * @copyright  2012 - 2013. Corpsee (poisoncorpsee@gmail.com)
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Core;

use Symfony\Component\HttpFoundation\Response;

//TODO: getVar/setVar, get/setTemplate, get/setGlobal, bind/bindGlobal
class Template
{
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
	protected $template_extension;

	/**
	 * @param string        $template_path
	 * @param string        $template_extension
	 * @param array         $data
	 * @param Response|NULL $response
	 */
	public function __construct ($template_path, $template_extension = '.tpl', array $data = array(), Response $response = NULL)
	{
		$this->template_path      = $template_path;
		$this->template_extension = $template_extension;
		$this->data               = $data;
		$this->response           = $response;
	}

	/*public function __get($data_name)
	{
		return $this->getData($data_name);
	}

	public function __set($data_name, $data_value)
	{
		$this->setData($data_name, $data_value);
	}

	public function __unset($data_name)
	{
		unset($this->data[$data_name]);
	}

	public function __isset($data_name)
	{
		return isset($this->data[$data_name]);
	}

	public function __toString()
	{

	}*/

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

	public function setResponse (Response $response)
	{
		$this->response = $response;
		return $this;
	}

	public function getResponse ()
	{
		return $this->response;
	}

	/**
	 * @param string|array $data_name
	 * @param string|NULL  $data_value
	 *
	 * @return Template
	 */
	public function setData ($data_name, $data_value = NULL)
	{
		if (is_array($data_name) && is_null($data_value))
		{
			foreach ($data_name as $var_name => $var_value)
			{
				$this->data[$var_name] = $var_value;
			}

		}
		else
		{
			$this->data[$data_name] = $data_value;
		}
		return $this;
	}

	/**
	 * @param string|NULL $data_name
	 *
	 * @return mixed
	 */
	public function getData ($data_name = NULL)
	{
		if (is_null($data_name))
		{
			return $this->data;
		}
		elseif (isset($this->data[$data_name]))
		{
			return $this->data[$data_name];
		}
		return NULL;
	}

	/**
	 * @param string $template
	 * @param array $data
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	protected function getTemplate ($template, $data = array())
	{
		if ($data) { $this->data = $data; }

		$path = $this->template_path . $template . $this->template_extension;

		extract($this->data, EXTR_SKIP);

		ob_start();

		try
		{
			include_once($path);
		}
		catch (\Exception $e)
		{
			ob_end_clean();
			throw $e;
		}
		return ob_get_clean();
	}

	/**
	 * @param string $template
	 * @param array $data
	 * @param Response $response
	 *
	 * @return Response
	 */
	public function render($template, array $data = array(), Response $response = NULL)
	{
		if (NULL === $response)
		{
			$response = new Response();
		}

		$response->setContent($this->getTemplate($template, $data));

		return $response;
	}
}