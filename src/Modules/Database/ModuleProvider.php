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

namespace Nameless\Modules\Database;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Database ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
    public function register()
    {
        $this->container['database.database'] = function ($container) {
            return new Database
            (
                $container['database.type'],
                $container['database.dns'],
                $container['database.user'],
                $container['database.password'],
                $container['database.persistent'],
                $container['database.compress']
            );
        };
    }
}
