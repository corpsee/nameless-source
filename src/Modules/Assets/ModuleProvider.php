<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Assets;

use Nameless\Core\Console;
use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Assets ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
    //TODO: assets lib packages like: $container['assets.dispatcher']->get['jquery']
    public function register()
    {
        $this->container['assets.dispatcher'] = function ($container) {
            return new AssetsDispatcher($container);
        };
    }

    /**
     * @param Console $console
     */
    public function registerConsole($console) {}
}
