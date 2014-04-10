<?php

namespace FBook\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Silex\Provider\SessionServiceProvider;
use Silex\SilexEvents;
use Symfony\Component\ClassLoader\MapFileClassLoader;
use FBook\Routing\Generator\UrlGenerator;
use FBook\Facebook;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * FBookServiceProvider
 *
 * @author Catalin Dumitrescu
 */
class FBookServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['url_generator'] = $app->share(function () use ($app) {
            $urlGenerator = new UrlGenerator($app['routes'], $app['request_context']);

            if (isset($app['fbook.canvas']) && $app['fbook.canvas'] && isset($app['fbook.namespace'])) {
                $urlGenerator->setNamespace($app['fbook.namespace']);
            }

            return $urlGenerator;
        });

        $app['fbook'] = $app->share(function () use ($app) {

            if (!isset($app['session'])) {
                $app->register(new SessionServiceProvider());
            }

            $parameters = array('app_id', 'secret', 'namespace', 'canvas', 'proxy', 'timeout', 'connect_timeout', 'permissions', 'protect');
            $config = array();
            foreach ($parameters as $parameter) {
                if (isset($app['fbook.'.$parameter])) {
                    $config[$parameter] = $app['fbook.'.$parameter];
                }
            }

            return new Facebook(
                $config,
                $app['session'],
                isset($app['monolog'])?$app['monolog']:null
            );
        });

        $app->before(function($request) use ($app) {
            $app['fbook']->setRequest($request);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
