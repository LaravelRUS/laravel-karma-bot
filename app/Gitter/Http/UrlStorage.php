<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 00:00
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Http;

/**
 * Class UrlStorage
 * @package App\Gitter\Http
 */
class UrlStorage
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var array
     */
    protected static $routes = [
        /* ---------------------
         *        Streams
         * --------------------- */
        'events'            => 'https://stream.gitter.im/v1/rooms/{roomId}/events',
        'messages'          => 'https://stream.gitter.im/v1/rooms/{roomId}/chatMessages',

        /* ---------------------
         *       Messages
         * --------------------- */
        // Additional arguments: limit, afterId, beforeId, skip
        'message.list'      => 'https://api.gitter.im/v1/rooms/{roomId}/chatMessages',
        // POST: {"text": "message text"}
        'message.send'      => 'https://api.gitter.im/v1/rooms/{roomId}/chatMessages',
        // PUT: {"text": "new text"}
        'message.update'    => 'https://api.gitter.im/v1/rooms/{roomId}/chatMessages/{messageId}',


        /* ---------------------
         *        Rooms
         * --------------------- */
        'room.list'         => 'https://api.gitter.im/v1/rooms',
        'room.info'         => 'https://api.gitter.im/v1/rooms/{roomId}',
        'room.users'        => 'https://api.gitter.im/v1/rooms/{roomId}/users',
        'room.channels'     => 'https://api.gitter.im/v1/rooms/{roomId}/channels',
        // POST: {"uri": "username/repo"}
        'room.join'         => 'https://api.gitter.im/v1/rooms/{roomId}',


        /* ---------------------
         *          User
         * --------------------- */
        'user'              => 'https://api.gitter.im/v1/user/{userId}',
        'user.current'      => 'https://api.gitter.im/v1/user',
        'user.rooms'        => 'https://api.gitter.im/v1/user/{userId}/rooms',
        'user.orgs'         => 'https://api.gitter.im/v1/user/{userId}/orgs',
        'user.repos'        => 'https://api.gitter.im/v1/user/{userId}/repos',
        'user.channels'     => 'https://api.gitter.im/v1/user/{userId}/channels',
        // POST: {"chat": [chatId, chatId]}
        'message.unread'    => 'https://api.gitter.im/v1/user/{userId}/rooms/{roomId}/unreadItems',
    ];


    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }


    /**
     * @param $name
     * @param array $args
     * @return mixed|string
     * @throws \InvalidArgumentException
     */
    public function route($name, array $args = []): string
    {
        if (!array_key_exists($name, static::$routes)) {
            $message = sprintf('%s route not found.', $name);
            throw new \InvalidArgumentException($message);
        }

        $args['access_token'] = $this->token;

        return $this->url(static::$routes[$name], $args);
    }


    /**
     * @param $url
     * @param array $args
     * @return mixed|string
     */
    public function url($url, array $args = []): string
    {
        $url .= '?';

        foreach ($args as $key => $value) {
            $value  = urlencode($value);
            $search = sprintf('{%s}', $key);

            if (!str_contains($url, $search)) {
                $url .= sprintf('&%s=%s', $key, $value);
            }
            $url = str_replace($search, $value, $url);
        }
        return $url;
    }
}
