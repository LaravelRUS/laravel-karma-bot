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

namespace Interfaces\Gitter\Room;

use Domains\Message;
use Illuminate\Foundation\Application;
use Interfaces\Gitter\Client;
use Interfaces\Gitter\Middleware\Storage;
use Interfaces\Gitter\Http\Stream;

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
     * @var Storage
     */
    protected $middleware;

    /**
     * @var Application
     */
    protected $app;

    public function __construct()
    {
        $this->app = \App::make('app');

        $this->middleware = new \Interfaces\Gitter\Middleware\Storage(
            $this->app
        );

        //$this->createSubscribersStorage();
    }

    /**
     * Create subscribers storage
     */
    protected function createSubscribersStorage()
    {
        $subscribers = \Config::get('gitter.subscribers');

        $storage = new \Interfaces\Gitter\Subscriber\Storage($this->app, $this);
        foreach ($subscribers as $subscriber) {
            $storage->add($subscriber);
        }
    }

    /**
     * Create middleware storage
     *
     * @param array $middleware
     */
    protected function setMiddleware(array $middleware)
    {
        foreach ($middleware as $class => $priority) {
            $this->middleware->add($class, $priority);
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