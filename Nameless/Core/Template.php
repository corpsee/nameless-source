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
	 * @var array
	 */
	protected static $global_data = array();

	/**
	 * @var boolean
	 */
	protected $template_escape = TRUE;

	/**
	 * @var boolean
	 */
	protected static $template_global_escape = TRUE;

	/**
	 * @var array
	 */
	protected $escapes = array();

	/**
	 * @var
	 */
	protected static $global_escapes = array();

	/**
	 * @var Response
	 */
	protected $response;

	/**
	 * @param string $template_path
	 * @param string $template_extension
	 */
	public function __construct ($template_path, $template_extension)
	{
		$this->template_path      = $template_path;
		$this->template_extension = $template_extension;
	}

	/**
	 * @param string|array $data_name
	 * @param mixed        $data_value
	 * @param bool         $escape
	 *
	 * @return Template
	 *
	 * @throws \InvalidArgumentException
	 */
	public function setData ($data_name, $data_value = NULL, $escape = TRUE)
	{
		if (is_array($data_name) && is_null($data_value))
		{
			$this->data            = $data_name;
			$this->template_escape = $escape;
		}
		elseif ($data_name)
		{
			$this->data[$data_name]    = $data_value;
			$this->escapes[$data_name] = $escape;
		}
		else
		{
			throw new \InvalidArgumentException('Invalid argument for set template data');
		}
		return $this;
	}

	/**
	 * @param array $escapes
	 *
	 * @return Template
	 */
	protected function setEscapes (array $escapes)
	{
		$this->escapes = $escapes;
		return $this;
	}

	/**
	 * @param string $data_name
	 *
	 * @return mixed
	 *
	 * @throws \OutOfBoundsException
	 */
	public function getData ($data_name = NULL)
	{
		if (is_null($data_name))
		{
			$data = array();
			foreach ($this->data as $data_name => $data_value)
			{
				if ((isset($this->escapes[$data_name]) && FALSE === $this->escapes[$data_name]) || FALSE === $this->template_escape)
				{
					$data[$data_name] = self::cleanXSS($data_value);
				}
				else
				{
					$data[$data_name] = self::escape($data_value);
				}
			}
			return $data;
		}
		elseif (isset($this->data[$data_name]))
		{
			if ((isset($this->escapes[$data_name]) && FALSE === $this->escapes[$data_name]) || FALSE === $this->template_escape)
			{
				$data_value = self::cleanXSS($this->data[$data_name]);
			}
			else
			{
				$data_value = self::escape($this->data[$data_name]);
			}
			return $data_value;
		}
		throw new \OutOfBoundsException('Value doesn`t exist in template data');
	}

	/**
	 * @param string $data_name
	 * @param mixed  $data_value
	 * @param bool   $escape
	 *
	 * @return Template
	 */
	public function bindData ($data_name, &$data_value, $escape = TRUE)
	{
		$this->data[$data_name]    = $data_value;
		$this->escapes[$data_name] = $escape;
		return $this;
	}

	/**
	 * @param string|array $data_name
	 * @param mixed        $data_value
	 * @param bool         $escape
	 *
	 * @throws \InvalidArgumentException
	 */
	public static function setGloabalData ($data_name, $data_value = NULL, $escape = TRUE)
	{
		if (is_array($data_name) && is_null($data_value))
		{
			self::$global_data            = $data_name;
			self::$template_global_escape = $escape;
		}
		elseif ($data_name)
		{
			self::$global_data[$data_name]    = $data_value;
			self::$global_escapes[$data_name] = $escape;
		}
		else
		{
			throw new \InvalidArgumentException('Invalid argument for set template global data');
		}
	}

	/**
	 * @param string|array $data_name
	 * @param mixed        $data_default
	 *
	 * @return mixed
	 * @throws \OutOfBoundsException
	 */
	public static function getGlobalData ($data_name = NULL, $data_default = NULL)
	{
		if (is_null($data_name))
		{
			$data = array();
			foreach (self::$global_data as $data_name => $data_value)
			{
				if ((isset(self::$global_escapes[$data_name]) && FALSE === self::$global_escapes[$data_name]) || FALSE === self::$template_global_escape)
				{
					$data[$data_name] = self::cleanXSS($data_value);
				}
				else
				{
					$data[$data_name] = self::escape($data_value);
				}
			}
			return $data;
		}
		elseif (isset(self::$global_data[$data_name]))
		{
			if ((isset(self::$global_escapes[$data_name]) && FALSE === self::$global_escapes[$data_name]) || FALSE === self::$template_global_escape)
			{
				$data_value = self::cleanXSS(self::$global_data[$data_name]);
			}
			else
			{
				$data_value = self::escape(self::$global_data[$data_name]);
			}
			return $data_value;
		}
		throw new \OutOfBoundsException('Value doesn`t exist in template data');
	}

	/**
	 * @param string $data_name
	 * @param mixed  $data_value
	 * @param bool   $escape
	 */
	public static function bindGlobalData ($data_name, &$data_value, $escape = TRUE)
	{
		self::$global_data[$data_name]    = $data_value;
		self::$global_escapes[$data_name] = $escape;
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
	 * @param string $template
	 */
	public function subTemplate ($template)
	{
		$subtamplate_instance = new static($this->template_path, $this->template_extension, $this->data, $template);
		$subtamplate_instance->setTemplate($template);
		$subtamplate_instance->setData($this->data, $this->template_escape);
		$subtamplate_instance->setEscapes($this->escapes);

		echo $subtamplate_instance->renderTemplate();
	}

	/**
	 * @param string   $template
	 * @param array    $data
	 * @param bool     $escape
	 * @param bool     $compress
	 * @param Response $response
	 *
	 * @return Response
	 */
	public function render($template = '', array $data = array(), $escape = TRUE, $compress = FALSE, Response $response = NULL)
	{
		if ($data)
		{
			$this->setData($data, $escape);
		}

		if ($template)
		{
			$this->setTemplate($template);
		}

		if ($response)
		{
			$this->setResponse($response);
		}
		elseif (is_null($this->response))
		{
			$this->setResponse(new Response());
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
		$content_parts = preg_split
		(
			'#(</?pre[^>]*>)|(</?script[^>]*>)|(</?style[^>]*>)|(</?textarea[^>]*>)#i',
			$content,
			-1,
			PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
		);

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
	 * @param $value
	 *
	 * @return mixed
	 * @throws \InvalidArgumentException
	 */
	public static function cleanXSS ($value)
	{
		$value = (string)$value;

		// Validate standard character entites and UTF16 two byte encoding
		$value = preg_replace('#(&#*\w+)[\x00-\x20]+;#i', '$1;', $value);
		$value = preg_replace('#(&#x*)([0-9a-f]+);#i', '$1$2;', $value);

		// Remove carriage returns
      	$value = preg_replace('#\r+#', '', $value);

		// Remove NULL characters
		$value = preg_replace('#\0+#', '', $value);
		$value = preg_replace('#(\\\\0)+#', '', $value);

		$keywords = array
		(
			'#\bj\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t\b#is', // javascript
			'#\bv\s*b\s*s\s*c\s*r\s*i\s*p\s*t\b#is', // vbscript
			'#\bv\s*b\s*s\s*c\s*r\s*p\s*t\b#is', // vbscrpt
			'#\bs\s*c\s*r\s*i\s*p\s*t\b#is', //script
			'#\ba\s*p\s*p\s*l\s*e\s*t\b#is', // applet
			'#\ba\s*l\s*e\s*r\s*t\b#is', // alert
			'#\bd\s*o\s*c\s*u\s*m\s*e\s*n\s*t\b#is', // document
			'#\bw\s*r\s*i\s*t\s*e\b#is', // write
			'#\bc\s*o\s*o\s*k\s*i\s*e\b#is', // cookie
			'#\bw\s*i\s*n\s*d\s*o\s*w\b#is' // window
		);

		// Compact exploded keywords like "j a v a s c r i p t"
		foreach ($keywords as $keyword)
		{
			$marches = array();
			preg_match_all($keyword, $value, $marches);

			foreach ($marches[0] as $match)
			{
				$value = str_replace($match, preg_replace('/\s*/', '', $match), $value);
			}
		}

		$keywords = array
		(
			'/<(a|img)[^>]*[^a-z](<script|<xss)[^>]*>/is',
			'/<(a|img)[^>]*[^a-z]document\.cookie[^>]*>/is',
			'/<(a|img)[^>]*[^a-z]vbscri?pt\s*:[^>]*>/is',
			'/<(a|img)[^>]*[^a-z]expression\s*\([^>]*>/is',
			'#vbscri?pt\s*:#is',
			'#javascript\s*:#is',
			'#<\s*embed.*swf#is',
			'#<(a|img)[^>]*[^a-z]alert\s*\([^>]*>#is',
			'#<(a|img)[^>]*[^a-z]javascript\s*:[^>]*>#is',
			'#<(a|img)[^>]*[^a-z]window\.[^>]*>#is',
			'#<(a|img)[^>]*[^a-z]document\.[^>]*>#is',
			'#<[^>]*[^a-z]on[a-z]*\s*=[^>]*>#is',
		);

		return preg_replace($keywords, '', $value);
	}

	public static function escape ($value)
	{
		$value = (string)$value;
		return htmlspecialchars($value, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
	}
}