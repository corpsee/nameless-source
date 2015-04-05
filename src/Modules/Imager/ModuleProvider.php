<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Imager;

use Nameless\Core\Console;
use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Imager ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
    public function register()
    {
        $this->container['imager.driver'] = function ($container) {
            $config = $container['imager'];
            $driver = '\\' . __NAMESPACE__ . '\\' . $config['driver_name'] . 'Driver';
            return new $driver();
        };

        $this->container['imager.image'] = function ($container) {
            return new Image($container['imager.driver']);
        };
    }

    /**
     * @param Console $console
     */
    public function registerConsole($console) {}
}
