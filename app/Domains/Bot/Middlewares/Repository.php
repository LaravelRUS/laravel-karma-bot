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

use Core\Io\Response;
use Domains\Room\Room;
use Domains\Message\Message;
use Domains\User\User;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Arr;

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
     * @var Response
     */
    private $response;

    /**
     * @var array|Middleware[]
     */
    private $middlewares = [];

    /**
     * @var array
     */
    private $ignoreUsers = [];

    /**
     * Middlewares constructor.
     * @param Container $container
     * @param Room $room
     * @param Response $response
     */
    public function __construct(Container $container, Room $room, Response $response)
    {
        $this->room = $room;
        $this->response = $response;
        $this->container = $container;
    }

    /**
     * @param User $user
     * @return Repository
     */
    public function ignore(User $user) : Repository
    {
        $this->ignoreUsers[] = $user->getIdentity();

        return $this;
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
     * @return array|Middleware[]
     */
    public function getRegisteredMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        // User are ignored?
        if (in_array($message->user->getIdentity(), $this->ignoreUsers, true)) {
            return null;
        }

        // Ok, just run them
        foreach ($this->middlewares as $middleware) {
            $result = $this->container->call([$middleware, 'handle'], [
                'user'     => $message->user,
                'room'     => $this->room,
                'message'  => $message,
                'response' => $this->response,
            ]);

            // Force send if there is any response
            if ($result) {
                return $result;
            }
        }

        return null;
    }
}
