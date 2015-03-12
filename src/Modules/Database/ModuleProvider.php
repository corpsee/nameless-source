<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
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
            $config = $container['database'];
            return new Database
            (
                $config['type'],
                $config['dns'],
                $config['user'],
                $config['password'],
                $config['persistent'],
                $config['compress']
            );
        };
    }
}
