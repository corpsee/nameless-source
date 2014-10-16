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

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Nameless\Modules\Database\Database;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Base controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Controller implements ControllerInterface
{
    /**
     * @var \Pimple
     */
    protected $container;

    /**
     * @param \Pimple $container
     */
    public function setContainer(\Pimple $container)
    {
        $this->container = $container;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->container['request'];
    }

    /**
     * @return Database
     * @throws \RuntimeException
     */
    protected function getDatabase()
    {
        if (isset($this->container['database.database'])) {
            return $this->container['database.database'];
        }
        throw new \RuntimeException('Don`t load module Database');
    }

    /**
     * @return Kernel
     */
    private function getKernel()
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
    public function forward($route, array $attributes = array(), array $query = array())
    {
        return $this->getKernel()->forward($route, $attributes, $query);
    }

    /**
     * External redirect
     *
     * @param string $url
     * @param int $status
     * @param array $headers
     *
     * @return RedirectResponse
     */
    public function redirect($url, $status = 302, $headers = array())
    {
        return new RedirectResponse($url, $status, $headers);
    }

    /**
     * Exception for 404 error
     *
     * @param string $message
     * @param \Exception $previous
     *
     * @throws NotFoundHttpException
     */
    public function notFound($message = 'Page not found', \Exception $previous = null)
    {
        throw new NotFoundHttpException($message, $previous);
    }

    /**
     * @param string $route_name
     * @param array $parameters
     * @param boolean|string $referenceType
     *
     * @return string
     */
    public function generateURL($route_name, array $parameters = [], $referenceType = UrlGenerator::ABSOLUTE_PATH)
    {
        return $this->container['url-generator']->generate($route_name, $parameters, $referenceType);
    }

    /**
     * @param string $template
     * @param array $data
     * @param integer $template_filter
     * @param array $filters
     * @param Response $response
     * @param string $template_extension
     * @param string $template_path
     *
     * @return Response
     */
    public function render($template, array $data = [], $template_filter = Template::FILTER_ESCAPE, array $filters = [], Response $response = null, $template_path = null, $template_extension = 'tpl')
    {
        if (is_null($template_path)) {
            $template_path = $this->container['templates_path'];
        }
        $template = new Template($template_path, $template, $data, $template_filter, $filters);
        return $template->render();
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
    public function get($key, $default = null, $deep = false)
    {
        return $this->getRequest()->get($key, $default, $deep);
    }

    /**
     * Get value from POST array by key. If $key is NULL then method returns all POST array
     *
     * @param string $key
     * @param mixed $default
     * @param boolean $deep
     *
     * @return mixed
     */
    public function getPost($key = null, $default = null, $deep = false)
    {
        if (is_null($key)) {
            return $this->getRequest()->request->all();
        }
        return $this->getRequest()->request->get($key, $default, $deep);
    }

    /**
     * Get value from GET array by key. If $key is NULL then method returns all GET array
     *
     * @param string $key
     * @param mixed $default
     * @param boolean $deep
     *
     * @return mixed
     */
    public function getGet($key = null, $default = null, $deep = false)
    {
        if (is_null($key)) {
            return $this->getRequest()->query->all();
        }
        return $this->getRequest()->query->get($key, $default, $deep);
    }

    /**
     * Get value from FILES array by key. If $key is NULL then method returns all FILES array
     *
     * @param string $key
     * @param mixed $default
     * @param boolean $deep
     *
     * @return mixed
     */
    public function getFiles($key = null, $default = null, $deep = false)
    {
        if (is_null($key)) {
            return $this->getRequest()->files->all();
        }
        return $this->getRequest()->files->get($key, $default, $deep);
    }

    /**
     * Get value from COOKIES array by key. If $key is NULL then method returns all COOKIES array
     *
     * @param string $key
     * @param mixed $default
     * @param boolean $deep
     *
     * @return mixed
     */
    public function getCookies($key = null, $default = null, $deep = false)
    {
        if (is_null($key)) {
            return $this->getRequest()->cookies->all();
        }
        return $this->getRequest()->cookies->get($key, $default, $deep);
    }

    /**
     * @return SessionInterface
     */
    public function getSession()
    {
        return $this->getRequest()->getSession();
    }

    /**
     * Get value from SERVER array by key. If $key is NULL then method returns all SERVER array
     *
     * @param string $key
     * @param mixed $default
     * @param boolean $deep
     *
     * @return mixed
     */
    public function getServer($key = null, $default = null, $deep = false)
    {
        if (is_null($key)) {
            return $this->getRequest()->server->all();
        }
        return $this->getRequest()->server->get($key, $default, $deep);
    }

    /**
     * Get http-header value by key. If $key is NULL then method returns all http-headers
     *
     * @param string $key
     * @param mixed $default
     * @param boolean $deep
     *
     * @return mixed
     */
    public function getHeaders($key = null, $default = null, $deep = false)
    {
        if (is_null($key)) {
            return $this->getRequest()->headers->all();
        }
        return $this->getRequest()->headers->get($key, $default, $deep);
    }

    /**
     * Get request attribute value by key. If $key is NULL then method returns all request attributes
     *
     * @param string $key
     * @param mixed $default
     * @param boolean $deep
     *
     * @return mixed
     */
    public function getAttributes($key = null, $default = null, $deep = false)
    {
        if (is_null($key)) {
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
    public function isMethod($method)
    {
        return $this->getRequest()->isMethod($method);
    }

    /**
     * Check for XmlHttpRequest (ajax)
     *
     * @return boolean
     */
    public function isAjax()
    {
        return $this->getRequest()->isXmlHttpRequest();
    }
}