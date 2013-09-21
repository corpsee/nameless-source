<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless\Core
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Core;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\FlattenException;

/**
 * ExceptionHandler class
 */
class ExceptionHandler
{
	/**
	 * @var boolean
	 */
	protected $environment;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @var string
	 */
	protected $templates_path;

	/**
	 * @var string
	 */
	protected $templates_extension;

	/**
	 * @param string          $templates_path
	 * @param string          $environment
	 * @param LoggerInterface $logger
	 * @param string          $templates_extension
	 *
	 * @return ExceptionHandler
	 */
	public static function register ($templates_path, $environment = 'debug', LoggerInterface $logger = NULL, $templates_extension = 'tpl')
	{
		$handler = new static($templates_path, $environment, $logger, $templates_extension);
		set_exception_handler(array($handler, 'handleException'));
	}

	/**
	 * @param string          $templates_path
	 * @param string          $environment
	 * @param LoggerInterface $logger
	 * @param string          $templates_extension
	 */
	public function __construct ($templates_path, $environment = 'debug', LoggerInterface $logger = NULL, $templates_extension = 'tpl')
	{
		$this->environment = $environment;
		$this->logger      = $logger;

		$this->templates_path      = $templates_path;
		$this->templates_extension = $templates_extension;
	}

	/**
	 * @param \Exception $exception
	 */
	public function handleException (\Exception $exception)
	{
		$this->createResponse($exception)->send();
	}

	/**
	 * @param \Exception $exception
	 *
	 * @return Response
	 */
	public function createResponse ($exception)
	{
		//echo $this->environment; exit;
		if (!$exception instanceof FlattenException)
		{
			$exception = FlattenException::create($exception);
		}

		$this->log($exception);

		if ($this->environment === 'debug')
		{
			$response_raw = $this->decorate($this->getContent($exception), 'Server error!');
			return new Response($response_raw, $exception->getStatusCode(), $exception->getHeaders());
		}
		else
		{
			$template_name      = $exception->getStatusCode();
			$template_path      = $this->templates_path;
			$template_extension = $this->templates_extension;

			if (!file_exists($template_path . $template_name . '.' . $template_extension))
			{
				$template_name = '500';

				if (!file_exists($template_path . $template_name . '.' . $template_extension))
				{
					$template_name      = $exception->getStatusCode();
					$template_path      = NAMELESS_PATH . 'Core' . DS . 'Templates' . DS;
					$template_extension = 'tpl';

					if (!file_exists($template_path . $template_name . '.' . $template_extension))
					{
						$template_name = '500';
					}
				}
			}

			$template = new Template($template_path, $template_name, array(), Template::FILTER_ESCAPE, array(), new Response('', $exception->getStatusCode(), $exception->getHeaders()), $template_extension);
			return $template->render();
		}
	}

	/**
	 * @param FlattenException $exception
	 */
	protected function log (FlattenException $exception)
	{
		$message = sprintf('%s: %s (uncaught exception) at %s line %s', get_class($exception), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		if (NULL !== $this->logger)
		{
			$this->logger->err($message);
		}
		else
		{
			error_log($message);
		}
	}

	/**
	 * @param FlattenException $exception
	 *
	 * @return string
	 */
	protected function getContent (FlattenException $exception)
	{
		$count   = count($exception->getAllPrevious());
		$content = '';
		foreach ($exception->toArray() as $position => $e)
		{
			$ind     = $count - $position + 1;
			$total   = $count + 1;
			$class   = $this->abbrClass($e['class']);
			$message = nl2br($e['message']);
			$content .= sprintf('<h2><span>%d/%d</span> %s: %s</h2><ol>', $ind, $total, $class, $message);
			foreach ($e['trace'] as $i => $trace)
			{
				$content .= '<li>';
				if ($trace['function'])
				{
					$content .= sprintf('at %s%s%s(%s)', $this->abbrClass($trace['class']), $trace['type'], $trace['function'], $this->formatArgs($trace['args']));
				}
				if (isset($trace['file']) && isset($trace['line']))
				{
					if ($linkFormat = ini_get('xdebug.file_link_format'))
					{
						$link = str_replace(array('%f', '%l'), array($trace['file'], $trace['line']), $linkFormat);
						$content .= sprintf(' in <a href="%s" title="Go to source">%s line %s</a>', $link, $trace['file'], $trace['line']);
					}
					else
					{
						$content .= sprintf(' in %s line %s', $trace['file'], $trace['line']);
					}
				}
				$content .= "</li>\n";
			}

			$content .= "    </ol>\n";
		}

		return $content;
	}

	/**
	 * @param string $content
	 * @param string $title
	 *
	 * @return string
	 */
	protected function decorate ($content, $title)
	{
		return '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/><meta name="robots" content="noindex, nofollow" /><title>' . $title . '</title></head><body><h1>' . $title . '</h1>' . $content . '</body></html>';
	}

	/**
	 * @param string $class
	 *
	 * @return string
	 */
	protected function abbrClass ($class)
	{
		$parts = explode('\\', $class);

		return sprintf("<abbr title=\"%s\">%s</abbr>", $class, array_pop($parts));
	}

	/**
	 * @param array $args
	 *
	 * @return string
	 */
	protected function formatArgs (array $args)
	{
		$result = array();
		foreach ($args as $key => $item)
		{
			if ('object' === $item[0])
			{
				$formattedValue = sprintf("<em>object</em>(%s)", $this->abbrClass($item[1]));
			}
			elseif ('array' === $item[0])
			{
				$formattedValue = sprintf("<em>array</em>(%s)", is_array($item[1]) ? $this->formatArgs($item[1]) : $item[1]);
			}
			elseif ('string' === $item[0])
			{
				$formattedValue = sprintf("'%s'", htmlspecialchars($item[1], ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8'));
			}
			elseif ('null' === $item[0])
			{
				$formattedValue = '<em>null</em>';
			}
			elseif ('boolean' === $item[0])
			{
				$formattedValue = '<em>' . strtolower(var_export($item[1], TRUE)) . '</em>';
			}
			elseif ('resource' === $item[0])
			{
				$formattedValue = '<em>resource</em>';
			}
			else
			{
				$formattedValue = str_replace("\n", '', var_export(htmlspecialchars((string)$item[1], ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8'), TRUE));
			}

			$result[] = is_int($key) ? $formattedValue : sprintf("'%s' => %s", $key, $formattedValue);
		}

		return implode(', ', $result);
	}
}
