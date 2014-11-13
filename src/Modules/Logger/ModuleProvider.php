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

namespace Nameless\Modules\Logger;

use Nameless\Core\ModuleProvider as BaseModuleProvider;
use Monolog\Handler\StreamHandler;

/**
 * Logger ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
    public function register()
    {
        //TODO: вызывать исключение, если не заданы необходимые настройки (['logger']['name'] например)
        $this->container['logger.logger'] = function ($container) {
            $config = $container['logger'];
            $logger = new Logger($config['name']);
            $logger->pushHandler($container['logger.handler']);
            return $logger;
        };

        $this->container['logger.handler'] = function ($container) {
            $config = $container['logger'];
            return new StreamHandler($config['path'] . $config['name'] . '.log', $container['logger.level']);
        };

        $this->container['logger.level'] = function ($container) {
            if ($container['environment'] == 'production') {
                return Logger::ERROR;
            } else {
                return Logger::DEBUG;
            }
        };
    }
}
