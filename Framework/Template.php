<?php

/*
 * This file is part of the Framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Framework;

use Symfony\Component\HttpFoundation\Response;

class Template
{
	/**
	 * @var array
	 */
	protected $data = array();

	/**
	 * @var string
	 */
	protected $template_path;

	/**
	 * @var string
	 */
	protected $template_extension;

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

		try { include_once($path); }
		catch (\Exception $e)
		{
			ob_end_clean();
			throw $e;
		}
		return ob_get_clean();
	}

	/**
	 * @param string $template
	 */
	public function getSubTemplate ($template)
	{
		echo $this->getTemplate($template);
	}

	/**
	 * @param string $template
	 * @param array $data
	 */
	public function renderTemplate ($template, array $data = array())
	{
		echo $this->getTemplate($template, $data);
	}

	/**
	 * @param string $template
	 * @param array $data
	 * @param Response $response
	 *
	 * @return Response
	 */
	public function renderResponse($template, array $data = array(), Response $response = NULL)
	{
		if (NULL === $response)
		{
			$response = new Response();
		}

		$response->setContent($this->getTemplate($template, $data));

		return $response;
	}
}