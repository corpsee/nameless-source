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

namespace Nameless\Modules\Auto;

use Nameless\Core\ModuleProvider as BaseModuleProvider;
use Nameless\Modules\Auto\User;

/**
 * Auto ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
    public function register()
    {
        $this->container['auto.user'] = function ($container) {
            return new User($container['session'], $container['routes-collection'], $container['auto.access']);
        };
    }
}
