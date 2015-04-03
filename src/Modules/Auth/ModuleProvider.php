<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Auth;

use Nameless\Core\ModuleProvider as BaseModuleProvider;
use Nameless\Modules\Auth\User;

/**
 * Auth ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
    public function register()
    {
        $this->container['auth.user'] = function ($container) {
            $config = $container['auth'];
            return new User($container['session.session'], $container['routes-collection'], $config['access']);
        };
    }
}

