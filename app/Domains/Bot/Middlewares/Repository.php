<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 17:45
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot\Middlewares;

use Domains\Room\Room;
use Domains\Message\Message;
use Illuminate\Contracts\Container\Container;

/**
 * Class Repository
 * @package Domains\Bot\Middlewares
 */
class Repository
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Room
     */
    private $room;

    /**
     * @var array|Middleware[]
     */
    private $middlewares = [];

    /**
     * Middlewares constructor.
     * @param Container $container
     * @param Room $room
     */
    public function __construct(Container $container, Room $room)
    {
        $this->room = $room;
        $this->container = $container;
    }

    /**
     * @param string $class
     * @return $this|Repository
     */
    public function register(string $class) : Repository
    {
        $instance = $this->container->make($class, [
            'room' => $this->room,
        ]);

        $this->middlewares[] = $instance;

        return $this;
    }

    /**
     * @param Message $message
     * @return Repository
     */
    public function handle(Message $message) : Repository
    {
        foreach ($this->middlewares as $middleware) {
            $this->container->call([$middleware, 'handle'], [
                'user'    => $message->user,
                'room'    => $this->room,
                'message' => $message,
            ]);
        }

        return $this;
    }
}
