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

use Symfony\Component\Console\Application as BaseApplication;


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
        $this->kernel = $kernel;
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