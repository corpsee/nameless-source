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
use Phinx\Config\Config as PhinxConfig;


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

        $this->kernel    = $kernel;
        $this->container = $this->kernel->getContainer();

        $phinx_config = $this->loadPhinxConfig();

        $this->add((new PhinxCommand\Create())->setConfig($phinx_config)->setName('migrations:create'));
        $this->add((new PhinxCommand\Migrate())->setConfig($phinx_config)->setName('migrations:migrate'));
        $this->add((new PhinxCommand\Rollback())->setConfig($phinx_config)->setName('migrations:rollback'));
        $this->add((new PhinxCommand\Status())->setConfig($phinx_config)->setName('migrations:status'));
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return PhinxConfig
     */
    protected function loadPhinxConfig()
    {
        $config = $this->getContainer()['migrations'];
        return new PhinxConfig($config, '');
    }
}