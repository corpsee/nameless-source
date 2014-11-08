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

namespace Nameless\Core;

use Pimple\Container as BaseContainer;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Generator\UrlGenerator;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\EventListener\LocaleListener;

use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcacheSessionHandler;


/**
 * Container class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Container extends BaseContainer
{
    public function init()
    {
        $this['logger.logger'] = null;
        $this['benchmark']     = null;

        $this->initRouting();
        $this->initDispatcher();
        $this->initLocalization();
        $this->initBenchmark();
        $this->initSession();
    }

    protected function initRouting()
    {
        $this['routes-collection'] = function () {
            return new RouteCollection();
        };

        $this['request-context'] = function ($container) {
            return new RequestContext($container['http_port'], $container['https_port']);
        };

        $this['url-matcher'] = function ($container) {
            return new UrlMatcher($container['routes-collection'], $container['request-context']);
        };

        $this['url-generator'] = function ($container) {
            return new UrlGenerator($container['routes-collection'], $container['request-context'], $container['logger.logger']);
        };

        $this['resolver'] = function ($container) {
            return new ControllerResolver($container, $container['logger.logger']);
        };
    }

    protected function initDispatcher()
    {
        $this['dispatcher'] = function ($container) {
            $dispatcher = new EventDispatcher();
            $dispatcher->addSubscriber(new RouterListener($container['url-matcher'], null, $container['logger.logger']));
            $dispatcher->addSubscriber(new LocaleListener($container['locale']));
            $dispatcher->addSubscriber(new NamelessListener($container['session'], $container['benchmark'], $container['logger.logger']));
            $dispatcher->addSubscriber(new ResponseListener('UTF-8'));

            return $dispatcher;
        };
    }

    protected function initLocalization()
    {
        $this['localization'] = function ($container) {
            return new Localization($container['language']);
        };
    }

    protected function initBenchmark()
    {
        $this['benchmark'] = function () {
            return new Benchmark();
        };
    }

    //TODO: Added MongoDB, Redis, PDO
    protected function initSession()
    {
        $session_type = $this['session.type'];

        switch ($session_type) {
            case 'files':
                $this['session.handler'] = function ($container) {
                    return new NativeFileSessionHandler($container['session.path']);
                };
                break;
            case 'memcache':
                $this['session.handler'] = function ($container) {
                    $path = explode(':', $container['session.path']);

                    $memcache = new \Memcache();
                    $memcache->connect($path[0], $path[1]);
                    return new MemcacheSessionHandler($memcache, $container['session.handler_options']);
                };
                break;
            case 'memcached':
                $this['session.handler'] = function ($container) {
                    $path = explode(':', $container['session.path']);

                    $memcached = new \Memcached();
                    $memcached->addServer($path[0], $path[1]);
                    return new MemcachedSessionHandler($memcached, $container['session.handler_options']);
                };
                break;
        }

        $this['session.storage'] = function ($container) {
            return new NativeSessionStorage($container['session.options'], $container['session.handler']);
        };

        $this['session'] = function ($container) {
            return new Session($container['session.storage']);
        };
    }
}