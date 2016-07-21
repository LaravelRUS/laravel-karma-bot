<?php
/**
 * This file is part of GitterBot package.
 *
 * @author butschster <butschster@gmail.com>
 * @date 20.07.2016 15:34
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Domains\Bot;

use Domains\Room\RoomInterface;
use Domains\User;

/**
 * Class AchieveSubscriber
 */
interface ClientInterface
{
    /**
     * @param RoomInterface $room
     * @param string        $message
     *
     * @return void
     */
    public function sendMessage(RoomInterface $room, $message);

    /**
     * @param RoomInterface $room
     *
     * @return void
     */
    public function listen(RoomInterface $room);

    /**
     * @return string
     */
    public function version();

    /**
     * @return ClientInterface
     */
    public function run(): ClientInterface;

    /**
     * @param string $id
     *
     * @return User
     */
    public function getUserById($id);
}