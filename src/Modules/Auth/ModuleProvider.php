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
