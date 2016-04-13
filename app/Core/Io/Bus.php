<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 12.04.2016 15:37
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io;

use Domains\Message\Message;
use Domains\User\User;
use Illuminate\Events\Dispatcher;

/**
 * Class Bus
 * @package Core\IoBus
 */
abstract class Bus implements Response, Request, Inclusion, Authentication
{
    const EVENT_NEW_MESSAGE = 'on:message';
    const EVENT_NEW_USER = 'on:message';
    const EVENT_NEW_ROOM = 'on:message';

        // Bus enable\disable options
    use InclusionTrigger,
        // Parse output data
        ResponseContentParser;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * Bus constructor.
     */
    public function __construct()
    {
        $this->events = new Dispatcher();
    }

    /**
     * @param \Closure $closure
     * @return Request
     */
    final public function onMessage(\Closure $closure) : Request
    {
        $this->events->listen(static::EVENT_NEW_MESSAGE, $closure);

        return $this;
    }

    /**
     * @param \Closure $closure
     * @return Request
     */
    final public function onRoom(\Closure $closure) : Request
    {
        $this->events->listen(static::EVENT_NEW_ROOM, $closure);

        return $this;
    }

    /**
     * @param \Closure $closure
     * @return Request
     */
    final public function onUser(\Closure $closure) : Request
    {
        $this->events->listen(static::EVENT_NEW_USER, $closure);

        return $this;
    }

    /**
     * @return void
     */
    abstract public function listen();

    /**
     * @param mixed $data
     * @return bool
     */
    abstract public function send($data) : bool;

    /**
     * @return User
     */
    abstract public function auth() : User;

    /**
     * @param Message $message
     * @param mixed $data
     * @return bool
     */
    abstract public function update(Message $message, $data) : bool;

    /**
     * @param string $event "on:message" or "on:user" or "on:room" string
     * @param array ...$args
     * @return array|null
     */
    protected function fire(string $event, ...$args)
    {
        return $this->events->fire($event, $args);
    }
}
