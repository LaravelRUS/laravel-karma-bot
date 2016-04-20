<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 16:14
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Room;

use Core\Mappers\RoomMapper\RoomMapper;
use Domains\Message\Message;

/**
 * Class Room
 * @package Domains\Room
 */
class Room extends RoomMapper
{
    /**
     * @param Message $message
     * @return Room
     */
    public function addMessage(Message $message) : Room
    {
        // $this->messages->add($message);
        // return $this;
    }
}
