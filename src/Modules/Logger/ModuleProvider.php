<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Logger;

use Nameless\Core\Console;
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
            return new StreamHandler(rtrim($config['path'], '/') . '/' . $config['name'] . '.log', $container['logger.level']);
        };

        $this->container['logger.level'] = function ($container) {
            if ($container['environment'] == 'production') {
                return Logger::ERROR;
            } else {
                return Logger::DEBUG;
            }
        };
    }

    /**
     * @param Console $console
     */
    public function registerConsole($console) {}
}
