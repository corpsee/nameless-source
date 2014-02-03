<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2014. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Core;

use Symfony\Component\HttpFoundation\Response;

/**
 * Template class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Template
{
	const FILTER_RAW    = 0;

	const FILTER_ESCAPE = 1;

	const FILTER_XSS    = 2;

	/**
	 * @var string
	 */
	protected $template_path;

	/**
	 * @var string
	 */
	protected $template_extension;

	/**
	 * @var string
	 */
	protected $template;

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var boolean
	 */
	protected $template_filter;

	/**
	 * @var array
	 */
	protected $filters;

	/**
	 * @var Response
	 */
	protected $response;

	/**
	 * @param string   $template_path
	 * @param string   $template
	 * @param array    $data
	 * @param integer  $template_filter
	 * @param array    $filters
	 * @param Response $response
	 * @param string   $template_extension
	 */
	//TODO: Page layout instead of subtemplates (or together)
	public function __construct ($template_path, $template, $data = array(), $template_filter = self::FILTER_ESCAPE, array $filters = array(), Response $response = NULL, $template_extension = 'tpl')
	{
		$this->template_path      = $template_path;
		$this->template_extension = $template_extension;
		$this->template           = $template;
		$this->template_filter    = $template_filter;
		$this->response           = $response;
		$this->filters            = $filters;

		$this->setData($data);
	}

	/**
	 * @param array|string $name
	 * @param mixed        $value
	 * @param integer      $filter
	 *
	 * @return $this
	 *
	 * @throws \InvalidArgumentException
	 */
	//TODO: make filters is a preffixes in the $data_name
	public function setData ($name, $value = NULL, $filter = NULL)
	{
		//var_dump($name); exit;
		if ($name && !is_null($value))
		{
			$this->data[$name] = $value;
			if (!is_null($filter))
			{
				$this->filters[$name] = $filter;
			}
		}
		elseif (is_array($name))
		{
			$this->data = $name;
		}
		else
		{
			throw new \InvalidArgumentException('Invalid argument for set template data');
		}
		return $this;
	}

	/**
	 * @param string|array $name
	 *
	 * @return mixed
	 *
	 * @throws \OutOfBoundsException
	 */
	public function getData ($name = NULL)
	{
		if (is_null($name))
		{
			$data = array();
			foreach ($this->data as $name => $data_value)
			{
				$filter = isset($this->filters[$name]) ? $this->filters[$name] : $this->template_filter;
				switch ($filter)
				{
					case 0:
						$data[$name] = $data_value;
						break;
					case 1:
						$data[$name] = self::escape($data_value);
						break;
					case 2:
						$data[$name] = $this->cleanXSS($data_value);
				}
			}
			return $data;
		}
		elseif (isset($this->data[$name]))
		{
			$filter = isset($this->filters[$name]) ? $this->filters[$name] : $this->template_filter;
			switch ($filter)
			{
				case 0:
					$return = $this->data[$name];
					break;
				case 1:
					$return =  self::escape($this->data[$name]);
					break;
				case 2:
					$return = $this->cleanXSS($this->data[$name]);
			}
			return $return;
		}
		throw new \OutOfBoundsException('Value doesn`t exist in template data');
	}

	/**
	 * @param string  $name
	 * @param mixed   $value
	 * @param integer $filter
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return $this
	 */
	public function bindData ($name, &$value, $filter = NULL)
	{
		$this->data[$name] = $value;
		if (!is_null($filter))
		{
			$this->filters[$name] = $filter;
		}
		if (!$name && !$value)
		{
			throw new \InvalidArgumentException('Invalid argument for bind template data');
		}
		return $this;
	}

	/**
	 * @param string $template
	 */
	public function subTemplate ($template)
	{
		$subtemplate_instance = new static($this->template_path, $template, $this->data, $this->template_filter, $this->filters, $this->response, $this->template_extension);
		return $subtemplate_instance->renderTemplate();
	}

	/**
	 * @param bool $compress
	 *
	 * @return Response
	 */
	public function render($compress = FALSE)
	{
		if (is_null($this->response))
		{
			$this->response = new Response();
		}

		$content = $this->renderTemplate();
		if ($compress)
		{
			$content = $this->compressHTML($content);
		}

		$this->response->setContent($content);
		return $this->response;
	}

	/**
	 * @return string
	 * @throws \RuntimeException
	 * @throws \Exception
	 */
	protected function renderTemplate ()
	{
		extract($this->getData(), EXTR_REFS);

		$template_path = $this->template_path . $this->template . '.' . $this->template_extension;
		if (!file_exists($template_path))
		{
			throw new \RuntimeException('Template file: ' . $template_path . ' doesn`t exist.');
		}

		ob_start();

		try
		{
			include_once $template_path;
		}
		catch (\Exception $exception)
		{
			ob_end_clean();
			throw $exception;
		}
		return ob_get_clean();
	}

	/**
	 * @param string $content
	 *
	 * @return string
	 */
	protected function compressHTML ($content)
	{
		$content_parts = preg_split('#(</?pre[^>]*>)|(</?script[^>]*>)|(</?style[^>]*>)|(</?textarea[^>]*>)#i', $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		$content       = '';
		$preserve_next = FALSE;
		$optimize_next = FALSE;

		foreach ($content_parts as $part)
		{
			if (strncasecmp($part, '<pre', 4) === 0 || strncasecmp($part, '<textarea', 9) === 0)
			{
				$preserve_next = TRUE;
			}
			elseif (strncasecmp($part, '<script', 7) === 0 || strncasecmp($part, '<style', 6) === 0)
			{
				$optimize_next = TRUE;
			}
			elseif ($preserve_next)
			{
				$preserve_next = FALSE;
			}
			elseif ($optimize_next)
			{
				$optimize_next = FALSE;

				$part = str_replace(array("/* <![CDATA[ */\n", "<!--\n", "\n//-->"), array('/* <![CDATA[ */', '', ''), $part);
				$part = trim(preg_replace(array('@(?<!:)//(?!W3C|DTD|EN).*@', '/[ \n\t]*(;|=|\{|\}|\[|\]|&&|,|<|>|\',|",|\':|":|: |\|\|)[ \n\t]*/'), array('', '$1'), $part));
			}
			else
			{
				$replace_array = array
				(
					'/\n ?\n+/'      => "\n",   // Convert multiple line-breaks
					'/^[\t ]+</m'    => '<',    // Remove tag indentation
					'/>( )?\n</'     => '>$1<', // Remove line-breaks between tags
					'/\n/'           => '',     // Remove all remaining line-breaks
					'/ <\/(div|p)>/' => '</$1>' // Remove spaces before closing DIV and P tags
				);

				$part = str_replace("\r", '', $part);
				$part = trim(preg_replace(array_keys($replace_array), array_values($replace_array), $part));
			}
			$content .= $part;
		}
		return $content;
	}

	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function cleanXSS ($value)
	{
		if (empty($value) || is_bool($value) || is_numeric($value))
		{
			return $value;
		}

		if (is_array($value) || is_object($value))
		{
			foreach ($value as $value_key => $value_item)
			{
				$value[$value_key] = $this->cleanXSS($value_item);
			}
			return $value;
		}

		// Remove all NULL bytes
		$value = str_replace("\0", '', $value);

		// Fix &entity\n;
		$value = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $value);
		$value = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $value);
		$value = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $value);
		$value = html_entity_decode($value, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$value = preg_replace('#(?:on[a-z]+|xmlns)\s*=\s*[\'"\x00-\x20]?[^\'>"]*[\'"\x00-\x20]?\s?#iu', '', $value);

		// Remove javascript: and vbscript: protocols
		$script_replace = array
		(
			'#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu',
			'#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu',
			'#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u',
		);
		$value = preg_replace($script_replace, '$1=$2[deleted]', $value);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$expression_replace = array
		(
			'#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#is',
			'#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#is',
			'#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#ius',
		);
		$value = preg_replace($expression_replace, '$1>', $value);

		// Remove namespaced elements (we do not need them)
		$value = preg_replace('#</*\w+:\w[^>]*+>#i', '', $value);

		do
		{
			// Remove really unwanted tags
			$old = $value;
			$value = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $value);
		}
		while ($old !== $value);

		return $value;
	}


	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	protected function escape ($value)
	{
		if (empty($value) || is_numeric($value) || is_bool($value))
		{
			return $value;
		}

		if (is_array($value) || is_object($value))
		{
			foreach ($value as $value_key => $value_item)
			{
				$value[$value_key] = $this->escape($value_item);
			}
			return $value;
		}
		return htmlspecialchars((string)$value, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
	}
}