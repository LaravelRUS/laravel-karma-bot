<?php
declare(strict_types = 1);
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 03.03.2016 15:00
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Core\Providers;

use Core\Doctrine\CacheBridge;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\ArrayCache;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Class AnnotationsServiceProvider
 * @package Core\Providers
 */
class AnnotationsServiceProvider extends ServiceProvider
{
    /**
     * Register class
     * @throws \InvalidArgumentException
     */
    public function register()
    {
        AnnotationRegistry::registerLoader(function ($class) {
            return class_exists($class);
        });

        $this->app->singleton(Reader::class, function (Application $app) {
            $debug = $app->make(Repository::class)->get('app.debug', false);
            $cache = $app->make($debug ? ArrayCache::class : CacheBridge::class);

            return new CachedReader(new AnnotationReader(), $cache, $debug);
        });

        $this->app->alias(AnnotationReader::class, Reader::class);
    }
}
