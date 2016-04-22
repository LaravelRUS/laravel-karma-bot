<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 15:10
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io;

use Core\Io\Redis\Io as RedisIo;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use React\EventLoop\LoopInterface;

/**
 * Class IoServiceProvider
 * @package Core\Io
 */
class IoServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IoInterface::class, function(Container $app){
            return new RedisIo(app('redis'), app(LoopInterface::class));
        });

        $this->app->alias(IoInterface::class, RedisIo::class);
    }
}