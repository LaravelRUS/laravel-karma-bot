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

use Core\Entity\Builder as Entity;
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

        Entity::fill($message, 'id', $data->id);
        Entity::fill($message, 'created', new \DateTime($data->sent));
        Entity::fill($message, 'updated', new \DateTime($data->lastAccessTime ?? $data->sent));


        if ($data->mentions ?? false) {
            foreach ($data->mentions as $mention) {
                if (!UserFactory::isValidMention($mention)) {
                    continue;
                }

                $message->addMention(UserFactory::createFromMention($mention));
            }
        }

        return $message;
    }

}
