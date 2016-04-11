<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 20:19
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Gitter\Factories;

use Domains\Room\Room as RoomEntity;
use Domains\Message\Message as MessageEntity;
use Interfaces\Gitter\Factories\User as UserFactory;

/**
 * Class Message
 * @package Interfaces\Gitter\Factories
 */
class Message
{
    /**
     * @param \StdClass $data
     * @param RoomEntity $room
     * @return MessageEntity
     */
    public static function create($data, RoomEntity $room)
    {
        $user = UserFactory::create($data->fromUser);

        $message = new MessageEntity($data->text, $room, $user);
        $message->gitterId = $data->id;
        $message->overwriteTimestamps($data->sent, $data->lastAccessTime ?? null);

        return $message;
    }
}
