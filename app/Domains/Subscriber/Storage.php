<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @author butschster <butschster@gmail.com>
 * @date 11.10.2015 8:26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Subscriber;

use Illuminate\Contracts\Container\Container;
use Domains\Room\RoomInterface;

/**
 * Class Storage
 */
class Storage
{
    /**
     * @var SubscriberInterface[]
     */
    protected $subscribers = [];

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var RoomInterface
     */
    protected $room;

    /**
     * Storage constructor.
     *
     * @param Container     $container
     * @param RoomInterface $room
     */
    public function __construct(Container $container, RoomInterface $room)
    {
        $this->container = $container;
        $this->room = $room;
    }

    /**
     * @param $class
     * @return $this
     */
    public function add($class)
    {
        $this->container->bind($class, $class);
        $subscriber = $this->container->make($class);

        $this->subscribers[] = $subscriber;

        $subscriber->handle($this->room);

        return $this;
    }
}