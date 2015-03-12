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

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Nameless\Core\ControllerInterface;

/**
 * AccessListener class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AccessListener implements EventSubscriberInterface
{
    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof ControllerInterface && method_exists($controller[0], 'before')) {
            $controller[0]->before();
        }
    }

    /**
     * @return array
     */
    static public function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController'],
        ];
    }
}