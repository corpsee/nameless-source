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

use Symfony\Component\Console\Application as BaseApplication;
use Nameless\Core\Application;
use Pimple\Container;


/**
 * Console class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Console extends BaseApplication
{
    /**
     * @var Application
     */
    protected $kernel;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Application $kernel
     * @param string $name
     * @param string $version
     */
    public function __construct(Application $kernel, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->kernel->getContainer();
    }
}