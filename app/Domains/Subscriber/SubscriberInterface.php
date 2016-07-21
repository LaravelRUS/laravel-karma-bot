<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @author butschster <butschster@gmail.com>
 *
 * @date 11.10.2015 8:27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Subscriber;

use Domains\Room\RoomInterface;

/**
 * Interface SubscriberInterface
 */
interface SubscriberInterface
{
    /**
     * @param RoomInterface $room
     *
     * @return mixed
     */
    public function handle(RoomInterface $room);
}