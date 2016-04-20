<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 19.04.2016 2:16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Providers;

use Core\Doctrine\CacheBridge;
use Core\Doctrine\EventBridge;
use Core\Doctrine\Events;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events as DeclaredEvents;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

/**
 * Class AnnotationsServiceProvider
 * @package Core\Providers
 */
class AnnotationsServiceProvider extends ServiceProvider
{
    /**
     * @throws \InvalidArgumentException
     */
    public function register()
    {
        AnnotationRegistry::registerLoader(function(string $class) {
            return class_exists($class);
        });


        $this->app->singleton(Reader::class, function(Container $app) {
            return new CachedReader(
                new AnnotationReader(),
                new CacheBridge($app->make('cache'))
            );
        });
    }
}