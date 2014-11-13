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
    /**
     * @param Application $application
     * @param array       $values
     */
    public function init(Application $application, array $values = [])
    {
        $this['kernel']        = $application;
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

    //TODO: move Localization to module
    protected function initLocalization()
    {
        $this['localization'] = function ($container) {
            return new Localization($container['language']);
        };
    }

    //TODO: move Benchmark to module
    protected function initBenchmark()
    {
        $this['benchmark'] = function () {
            return new Benchmark();
        };
    }

    //TODO: Added MongoDB, Redis, PDO
    protected function initSession()
    {
        $config = $this['session'];

        switch ($config['type']) {
            case 'files':
                $this['session.handler'] = function ($container) {
                    $config = $container['session'];
                    return new NativeFileSessionHandler($config['path']);
                };
                break;
            case 'memcache':
                $this['session.handler'] = function ($container) {
                    $config = $container['session'];
                    $path = explode(':', $config['path']);

                    $memcache = new \Memcache();
                    $memcache->connect($path[0], $path[1]);
                    return new MemcacheSessionHandler($memcache, $config['handler_options']);
                };
                break;
            case 'memcached':
                $this['session.handler'] = function ($container) {
                    $config = $container['session'];
                    $path = explode(':', $config['path']);

                    $memcached = new \Memcached();
                    $memcached->addServer($path[0], $path[1]);
                    return new MemcachedSessionHandler($memcached, $config['handler_options']);
                };
                break;
        }

        $this['session.storage'] = function ($container) {
            $config = $container['session'];
            return new NativeSessionStorage($config['options'], $config['handler']);
        };

        $this['session'] = function ($container) {
            return new Session($container['session.storage']);
        };
    }
}