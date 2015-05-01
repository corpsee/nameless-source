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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application as BaseApplication;
use Phinx\Console\Command as PhinxCommand;

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
     * @param string      $name
     * @param string      $version
     */
    public function __construct(Application $kernel, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);

        $this->kernel    = $kernel;
        $this->container = $this->kernel->getContainer();

        $this->initModules();
    }

    protected function initModules()
    {
        if (isset($this->container['modules'])) {
            foreach ($this->container['modules'] as $module) {
                $this->kernel->getModuleProvider($module)->registerConsole($this);
            }
        }
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
