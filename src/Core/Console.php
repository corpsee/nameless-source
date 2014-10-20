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

use Symfony\Component\Console\Application;
use Pimple\Container;

/**
 * Kernel class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Console extends Application
{
    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Kernel $kernel
     * @param string $name
     * @param string $version
     */
    public function __construct(Kernel $kernel, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        $this->kernel = $kernel->boot();
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