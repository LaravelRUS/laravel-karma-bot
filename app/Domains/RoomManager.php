<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @author butschster <butschster@gmail.com>
 * @date 09.10.2015 17:08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains;

use Domains\Room\RoomInterface;
use Illuminate\Support\Collection;
use Interfaces\Gitter\StandartGitterRoom;

/**
 * Class RoomManager
 * @package Domains
 */
class RoomManager
{
    /**
     * @var Collection
     */
    protected $rooms;

    /**
     * RoomManager constructor.
     *
     * @param array $rooms
     */
    public function __construct(array $rooms = [])
    {
        $this->rooms = new Collection();
    }

    /**
     * @param RoomInterface $room
     *
     * @return $this
     */
    public function register(RoomInterface $room)
    {
        $this->rooms->put($room->id(), $room);

        return $this;
    }

    /**
     * @param string $id
     *
     * @return RoomInterface|null
     */
    public function get($id)
    {
        if (is_null($room = $this->rooms->get($id))) {
            return $this->rooms->filter(function(RoomInterface $room) use($id) {
                return $room->alias() == $id;
            })->first();
        }

        return $room;
    }
}
