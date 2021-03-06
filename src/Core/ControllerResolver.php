<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
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
    public function __construct(Container $container, LoggerInterface $logger = null)
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

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $controller = new $class();
        if ($controller instanceof ControllerInterface) {
            $controller->setContainer($this->container);
        }

        return [
            $controller,
            $method
        ];
    }
}

