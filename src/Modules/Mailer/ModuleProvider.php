<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Mailer;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Mailer ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
    public function register()
    {
        $this->container['mailer.mailer'] = function ($container) {
            return new \Swift_Mailer($container['mailer.transport']);
        };

        $this->container['mailer.transport'] = function ($container) {
            return new \Swift_Transport_MailTransport($container['mailer.transport_invoker'], $container['mailer.transport_eventdispatcher']);
        };

        $this->container['mailer.transport_invoker'] = function () {
            return new \Swift_Transport_SimpleMailInvoker();
        };

        $this->container['mailer.transport_eventdispatcher'] = function () {
            return new \Swift_Events_SimpleEventDispatcher();
        };
    }
}
