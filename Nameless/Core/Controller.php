<?php

/*
 * This file is part of the Nameless framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Nameless\Core;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Base controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Controller implements ControllerInterface
{
	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * @param Container $container
	 */
	public function setContainer(\Pimple $container = NULL)
	{
		$this->container = $container;
	}

	/**
	 * @return Request
	 */
	public function getRequest ()
	{
		return $this->container['request'];
	}

	/**
	 * @return Database
	 */
	public function getDatabase ()
	{
		return $this->container['database'];
	}

	/**
	 * @return Kernel
	 */
	private function getKernel ()
	{
		return $this->container['kernel'];
	}

	/**
	 * Internal redirect without creating an instance of kernel
	 *
	 * @param string $route
	 * @param array $attributes
	 * @param array $query
	 *
	 * @return Response
	 */
	public function forward ($route, array $attributes = array(), array $query = array())
	{
		return $this->getKernel()->forward($route, $attributes, $query);
	}

	/**
	 * External redirect
	 *
	 * @param string $url
	 * @param integer $status
	 *
	 * @return RedirectResponse
	 */
	public function redirect ($url, $status = 302)
	{
		return new RedirectResponse($url, $status);
	}

	/**
	 * Exception for 404 error
	 *
	 * @param string $message
	 * @param \Exception|null $previous
	 *
	 * @throws NotFoundHttpException
	 */
	public function notFound($message = 'Not Found', \Exception $previous = NULL)
	{
		throw new NotFoundHttpException($message, $previous);
	}

	/**
	 * Template render method
	 *
	 * @param string $template
	 * @param array $data
	 * @param Response|null $response
	 *
	 * @return Response
	 */
	public function render ($template, array $data = array(), Response $response = NULL)
	{
		$template_obj = new Template
		(
			$this->container['templates_path'],
			$this->container['templates_extension']
		);
		return $template_obj->renderResponse($template, $data, $response);
	}

	/**
	 * Get "parameter" value. Order: GET, PATH, POST, COOKIE
	 *
	 * @param string $key
	 * @param mixed $default
	 * @param boolean $deep
	 *
	 * @return mixed
	 */
	public function get ($key, $default = NULL, $deep = FALSE)
	{
		return $this->getRequest()->get($key, $default, $deep);
	}

	/**
	 * Get value from POST array by key. If $key is NULL then method returns all POST array
	 *
	 * @param string|null $key
	 * @param mixed $default
	 * @param boolean $deep
	 *
	 * @return mixed
	 */
	public function getPost ($key = NULL, $default = NULL, $deep = FALSE)
	{
		if (is_null($key))
		{
			return $this->getRequest()->request->all();
		}
		return $this->getRequest()->request->get($key, $default, $deep);
	}

	/**
	 * Get value from GET array by key. If $key is NULL then method returns all GET array
	 *
	 * @param string|null $key
	 * @param mixed $default
	 * @param boolean $deep
	 *
	 * @return mixed
	 */
	public function getGet ($key = NULL, $default = NULL, $deep = FALSE)
	{
		if (is_null($key))
		{
			return $this->getRequest()->query->all();
		}
		return $this->getRequest()->query->get($key, $default, $deep);
	}

	/**
	 * Get value from FILES array by key. If $key is NULL then method returns all FILES array
	 *
	 * @param string|null $key
	 * @param mixed $default
	 * @param boolean $deep
	 *
	 * @return mixed
	 */
	public function getFiles ($key = NULL, $default = NULL, $deep = FALSE)
	{
		if (is_null($key))
		{
			return $this->getRequest()->files->all();
		}
		return $this->getRequest()->files->get($key, $default, $deep);
	}

	/**
	 * Get value from COOKIES array by key. If $key is NULL then method returns all COOKIES array
	 *
	 * @param string|null $key
	 * @param mixed $default
	 * @param boolean $deep
	 *
	 * @return mixed
	 */
	public function getCookies ($key = NULL, $default = NULL, $deep = FALSE)
	{
		if (is_null($key))
		{
			return $this->getRequest()->cookies->all();
		}
		return $this->getRequest()->cookies->get($key, $default, $deep);
	}

	/**
	 * Get value from SERVER array by key. If $key is NULL then method returns all SERVER array
	 *
	 * @param string|null $key
	 * @param mixed $default
	 * @param boolean $deep
	 *
	 * @return mixed
	 */
	public function getServer ($key = NULL, $default = NULL, $deep = FALSE)
	{
		if (is_null($key))
		{
			return $this->getRequest()->server->all();
		}
		return $this->getRequest()->server->get($key, $default, $deep);
	}

	/**
	 * Get http-header value by key. If $key is NULL then method returns all http-headers
	 *
	 * @param string|null $key
	 * @param mixed $default
	 * @param boolean $deep
	 *
	 * @return mixed
	 */
	public function getHeaders ($key = NULL, $default = NULL, $deep = FALSE)
	{
		if (is_null($key))
		{
			return $this->getRequest()->headers->all();
		}
		return $this->getRequest()->headers->get($key, $default, $deep);
	}

	/**
	 * Get request attribute value by key. If $key is NULL then method returns all request attributes
	 *
	 * @param string|null $key
	 * @param mixed $default
	 * @param boolean $deep
	 *
	 * @return mixed
	 */
	public function getAttributes ($key = NULL, $default = NULL, $deep = FALSE)
	{
		if (is_null($key))
		{
			return $this->getRequest()->attributes->all();
		}
		return $this->getRequest()->attributes->get($key, $default, $deep);
	}

	/**
	 * Check for request method (POST, GET, PUT)
	 *
	 * @param string $method
	 *
	 * @return boolean
	 */
	public function isMethod ($method)
	{
		return $this->getRequest()->isMethod($method);
	}

	/**
	 * Check for XmlHttpRequest (ajax)
	 *
	 * @return boolean
	 */
	public function isAjax ()
	{
		return $this->container['request']->isXmlHttpRequest();
	}
}