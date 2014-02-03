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

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseControllerResolver;

/**
 * Controller resolver
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ControllerResolver extends BaseControllerResolver
{
	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * @param Container $container
	 * @param LoggerInterface $logger
	 */
	public function __construct(\Pimple $container, LoggerInterface $logger = NULL)
	{
		$this->container = $container;
		parent::__construct($logger);
	}

	/**
	 * @param string $controller
	 *
	 * @return array
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function createController($controller)
	{
		list($class, $method) = explode('::', $controller, 2);

		if (!class_exists($class))
		{
			throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
		}

		$controller = new $class();
		if ($controller instanceof ControllerInterface)
		{
			$controller->setContainer($this->container);
		}

		return array($controller, $method);
	}
}
