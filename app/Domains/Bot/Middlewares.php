<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 13.04.2016 15:56
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot;

use Core\Io\Response;
use Domains\Bot\Middlewares\Repository;
use Domains\Room\Room;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Container\Container;

/**
 * Class Middlewares
 * @package Domains\Bot
 */
class Middlewares
{
    /**
     * @param Container $container
     * @param Room $room
     * @param Response $response
     * @return Repository
     */
    public static function new(Container $container, Room $room, Response $response) : Repository
    {
        /** @var Config $config */
        $config = $container->make(Config::class);
        $middlewares = new Repository($container, $room, $response);

        // Export middlewares from config
        foreach ($config->get('gitter.middlewares') as $middleware) {
            $middlewares->register($middleware);
        }

        return $middlewares;
    }
}
