<?php

/*
 * This file is part of the Framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Framework;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\FlattenException;

//if (!defined('ENT_SUBSTITUTE')) { define('ENT_SUBSTITUTE', 8); }

/**
 * ExceptionHandler for production environment
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ExceptionHandlerProduction
{
	/**
	 * @var string
	 */
	protected $templates_path;

	/**
	 * @var string
	 */
	protected $templates_extension;

	/**
	 * @param string $templates_path
	 * @param string $templates_extension
	 */
	public function __construct ($templates_path, $templates_extension = '.tpl')
	{
		$this->templates_path      = $templates_path;
		$this->templates_extension = $templates_extension;
	}

	/**
	 * @param string $templates_path
	 * @param string $templates_extension
	 *
	 * @return ExceptionHandlerProduction
	 */
	public static function register ($templates_path, $templates_extension = '.tpl')
	{
		$handler = new static($templates_path, $templates_extension);
		set_exception_handler(array($handler, 'handle'));
		return $handler;
	}

	/**
	 * @param \Exception $exception
	 */
	public function handle (\Exception $exception)
	{
		$this->createResponse($exception)->send();
	}
	/**
	 * @param \Exception $exception
	 * @return Response
	 */
	public function createResponse (\Exception $exception)
	{
		if (!$exception instanceof FlattenException)
		{
			$exception = FlattenException::create($exception);
		}

		try
		{
			$response = new Response('', $exception->getStatusCode(), $exception->getHeaders());

			$template_obj = new Template
			(
				$this->templates_path,
				$this->templates_extension
			);

			return $template_obj->renderResponse($exception->getStatusCode(), array(), $response);
		}
		catch (\Exception $e)
		{
			//echo '<pre>'; print_r($e); exit;
			return new Response('<h1>Server error!</h2>', $exception->getStatusCode(), $exception->getHeaders());
		}
	}
}
