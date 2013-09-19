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
	protected $filters = array();

	/**
	 * @var Response
	 */
	protected $response;

	/**
	 * @param string   $template_path
	 * @param string   $template
	 * @param array    $data
	 * @param int      $template_filter
	 * @param Response $response
	 * @param string   $template_extension
	 */
	public function __construct ($template_path, $template, $data = array(), $template_filter = self::FILTER_ESCAPE, Response $response = NULL, $template_extension = 'tpl')
	{
		$this->template_path      = $template_path;
		$this->template_extension = $template_extension;
		$this->template           = $template;
		$this->template_filter    = $template_filter;
		$this->response           = $response;

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
	 * @param array $filters
	 *
	 * @return $this
	 */
	protected function setFilters (array $filters)
	{
		$this->filters = $filters;
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
					return $this->data[$name];
					break;
				case 1:
					return self::escape($this->data[$name]);
					break;
				case 2:
					return $this->cleanXSS($this->data[$name]);
			}
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
		$subtemplate_instance = new static($this->template_path, $template, $this->data, $this->template_filter, NULL, $this->template_extension);
		$subtemplate_instance->setFilters($this->filters);

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
	 * Clean a value and try to prevent XSS attacks
	 *
	 * @param mixed   $value      A string or array
	 *
	 * @return mixed The cleaned string or array
	 */
	protected function cleanXSS ($value)
	{
		if ($value === NULL || $value == '')
		{
			return $value;
		}

		// Recursively clean arrays
		if (is_array($value))
		{
			foreach ($value as $k => $v)
			{
				$value[$k] = static::cleanXSS($v);
			}

			return $value;
		}

		// Return if var is not a string
		if (is_bool($value) || $value === NULL || is_numeric($value))
		{
			return $value;
		}

		// Validate standard character entites and UTF16 two byte encoding
		$value = preg_replace('/(&#*\w+)[\x00-\x20]+;/i', '$1;', $value);
		$value = preg_replace('/(&#x*)([0-9a-f]+);/i', '$1$2;', $value);

		// Remove carriage returns
		$value = preg_replace('/\r+/', '', $value);

		// Replace unicode entities
		//$varValue = utf8_decode_entities($varValue);

		// Remove NULL characters
		$value = preg_replace('/\0+/', '', $value);
		$value = preg_replace('/(\\\\0)+/', '', $value);

		$keywords = array
		(
			'/\bj\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t\b/is', // javascript
			'/\bv\s*b\s*s\s*c\s*r\s*i\s*p\s*t\b/is', // vbscript
			'/\bv\s*b\s*s\s*c\s*r\s*p\s*t\b/is', // vbscrpt
			'/\bs\s*c\s*r\s*i\s*p\s*t\b/is', //script
			'/\ba\s*p\s*p\s*l\s*e\s*t\b/is', // applet
			'/\ba\s*l\s*e\s*r\s*t\b/is', // alert
			'/\bd\s*o\s*c\s*u\s*m\s*e\s*n\s*t\b/is', // document
			'/\bw\s*r\s*i\s*t\s*e\b/is', // write
			'/\bc\s*o\s*o\s*k\s*i\s*e\b/is', // cookie
			'/\bw\s*i\s*n\s*d\s*o\s*w\b/is' // window
		);

		// Compact exploded keywords like "j a v a s c r i p t"
		foreach ($keywords as $keyword)
		{
			$matches = array();
			preg_match_all($keyword, $value, $matches);

			foreach ($matches[0] as $match)
			{
				$value = str_replace($match, preg_replace('/\s*/', '', $match), $value);
			}
		}

		$regexp[] = '/<(a|img)[^>]*[^a-z](<script|<xss)[^>]*>/is';
		$regexp[] = '/<(a|img)[^>]*[^a-z]document\.cookie[^>]*>/is';
		$regexp[] = '/<(a|img)[^>]*[^a-z]vbscri?pt\s*:[^>]*>/is';
		$regexp[] = '/<(a|img)[^>]*[^a-z]expression\s*\([^>]*>/is';

		$regexp[] = '/vbscri?pt\s*:/is';
		$regexp[] = '/javascript\s*:/is';
		$regexp[] = '/<\s*embed.*swf/is';
		$regexp[] = '/<(a|img)[^>]*[^a-z]alert\s*\([^>]*>/is';
		$regexp[] = '/<(a|img)[^>]*[^a-z]javascript\s*:[^>]*>/is';
		$regexp[] = '/<(a|img)[^>]*[^a-z]window\.[^>]*>/is';
		$regexp[] = '/<(a|img)[^>]*[^a-z]document\.[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onabort\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onblur\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onchange\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onclick\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onerror\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onfocus\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onkeypress\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onkeydown\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onkeyup\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onload\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onmouseover\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onmouseup\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onmousedown\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onmouseout\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onreset\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onselect\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onsubmit\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onunload\s*=[^>]*>/is';
		$regexp[] = '/<[^>]*[^a-z]onresize\s*=[^>]*>/is';

		return preg_replace($regexp, '', $value);
	}

	/**
	 * @param string $value
	 *
	 * @return string
	 */
	protected function escape ($value)
	{
		if (empty($value) || is_numeric($value) || is_bool($value))
		{
			return (string)$value;
		}

		if (is_array($value))
		{
			foreach ($value as $k => $v)
			{
				$value[$k] = $this->escape($v);
			}
			return $value;
		}

		$value = (string)$value;
		return htmlspecialchars($value, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
	}
}