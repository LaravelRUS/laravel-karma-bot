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
    const DEFAULT_MIDDLEWARES_GROUP = 'common';

    /**
     * @param Container $container
     * @param Room $room
     * @param Response $response
     * @return Repository
     */
    public static function new(Container $container, Room $room, Response $response) : Repository
    {
        /** @var Config $config */
        $config      = $container->make(Config::class);

        $repository  = new Repository($container, $room, $response);

        $middlewares = static::getMiddlewares(
            $config,
            static::getMiddlewareGroups($config, $room)
        );

        foreach ($middlewares as $middleware) {
            $repository->register($middleware);
        }

        return $repository;
    }

    /**
     * @param Config $config
     * @param array $groups
     * @return array
     */
    private static function getMiddlewares(Config $config, array $groups) : array
    {
        $result = [];

        foreach ($groups as $group) {
            $result = array_merge($result, (array)$config->get('gitter.middlewares.' . $group));
        }

        return $result;
    }

    /**
     * @param Config $config
     * @param Room $room
     * @return array
     */
    private static function getMiddlewareGroups(Config $config, Room $room) : array
    {
        $roomName = trim($room->url, '/');

        $groups = (array)$config->get('gitter.rooms.' . $roomName);

        if ($groups === []) {
            $groups = [static::DEFAULT_MIDDLEWARES_GROUP];
        }

        return $groups;
    }
}
