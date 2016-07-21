<?php
/**
 * This file is part of GitterBot package.
 *
 * @author butschster <butschster@gmail.com>
 * @date   20.07.2016 15:27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Domains\Room;

use Domains\Bot\ClientInterface;
use Illuminate\Foundation\Application;
use Interfaces\Gitter\Client;

abstract class AbstractRoom implements RoomInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var array
     */
    protected $groups;

    /**
     * @var \Domains\Middleware\Storage
     */
    protected $middleware;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Application
     */
    protected $app;

    public function __construct()
    {
        $this->app = \App::make('app');

        $this->middleware = new \Domains\Middleware\Storage(
            $this->app
        );

        $this->client = app('bot.manager')->driver($this->driver());

        $this->createSubscribersStorage();
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function alias()
    {
        return $this->alias;
    }

    /**
     * @return array
     */
    public function groups()
    {
        return (array) $this->groups;
    }

    /**
     * @return ClientInterface
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * @return \Domains\Middleware\Storage
     */
    public function middleware()
    {
        return $this->middleware;
    }

    /**
     * Create subscribers storage
     */
    protected function createSubscribersStorage()
    {
        $subscribers = \Config::get('gitter.subscribers');

        $storage = new \Domains\Subscriber\Storage(
            $this->app,
            $this
        );

        foreach ($subscribers as $subscriber) {
            $storage->add($subscriber);
        }
    }

    /**
     * Create middleware storage
     *
     * @param array $groups
     */
    protected function setMiddleware(array $groups)
    {
        $registerAll = in_array('*', $this->groups());

        foreach ($groups as $group => $middleware) {
            if (is_array($middleware)) {
                if ($registerAll or in_array($group, $this->groups())) {
                    foreach ($middleware as $class) {
                        $this->middleware->add($class);
                    }
                }
            } else {

                $this->middleware->add($group);
            }
        }
    }

    /**
     * @return Client
     */
    public function listen()
    {
        $this->client()->listen($this);
    }

    /**
     * @param string $message
     * @return $this
     */
    public function sendMessage($message)
    {
        $this->client()->sendMessage($this, $message);
    }
}