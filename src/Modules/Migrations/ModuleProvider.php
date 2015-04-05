<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Migrations;

use Nameless\Core\Console;
use Phinx\Console\Command as PhinxCommand;
use Phinx\Config\Config as PhinxConfig;
use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Migrations ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
    public function register() {}

    /**
     * @param Console $console
     */
    public function registerConsole($console)
    {
        $config       = $this->getContainer()['migrations'];
        $phinx_config = new PhinxConfig($config, '');

        $console->add(
            (new PhinxCommand\Create())
                ->setConfig($phinx_config)
                ->setName('migrations:create')
        );
        $console->add(
            (new PhinxCommand\Migrate())
                ->setConfig($phinx_config)
                ->setName('migrations:migrate')
        );
        $console->add(
            (new PhinxCommand\Rollback())
                ->setConfig($phinx_config)
                ->setName('migrations:rollback')
        );
        $console->add(
            (new PhinxCommand\Status())
                ->setConfig($phinx_config)
                ->setName('migrations:status')
        );
    }
}
