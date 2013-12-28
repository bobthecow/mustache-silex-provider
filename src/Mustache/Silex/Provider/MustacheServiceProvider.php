<?php

/*
 * This file is part of Mustache.php.
 *
 * (c) 2013 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mustache\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Mustache integration for Silex.
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
class MustacheServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['mustache.options'] = array();

        $app['mustache'] = $app->share(function ($app) {
            $defaults = array(
                'loader'          => $app['mustache.loader'],
                'partials_loader' => $app['mustache.partials_loader'],
                'helpers'         => $app['mustache.helpers'],
                'charset'         => $app['charset'],
            );

            if (isset($app['logger'])) {
                $defaults['logger'] = $app['logger'];
            }

            $app['mustache.options'] = array_replace($defaults, $app['mustache.options']);

            return new \Mustache_Engine($app['mustache.options']);
        });

        $app['mustache.loader'] = $app->share(function ($app) {
            if (!isset($app['mustache.path'])) {
                return new \Mustache_Loader_StringLoader;
            }

            return new \Mustache_Loader_FilesystemLoader($app['mustache.path']);
        });

        $app['mustache.partials_loader'] = $app->share(function ($app) {
            if (isset($app['mustache.partials_path'])) {
                return new \Mustache_Loader_FilesystemLoader($app['mustache.partials_path']);
            } elseif (isset($app['mustache.partials'])) {
                return new \Mustache_Loader_ArrayLoader($app['mustache.partials']);
            } else {
                return $app['mustache.loader'];
            }
        });

        $app['mustache.helpers'] = array();
    }

    public function boot(Application $app)
    {
        // nada
    }
}
