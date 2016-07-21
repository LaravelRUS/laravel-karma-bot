<?php
/**
 * This file is part of GitterBot package.
 *
 * @author butschster <butschster@gmail.com>
 * @date 20.207.2016 17:08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Slack;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Domains\Room\RoomInterface;
use Interfaces\Gitter\Support\AttributeMapper;

class MessageMapper implements Arrayable
{
    /**
     * @var array
     */
    protected $attributes;

    /**
     * MessageMapper constructor.
     *
     * @param RoomInterface $room
     * @param array         $attributes
     */
    public function __construct(RoomInterface $room, $attributes)
    {
        $fields = ['gitter_id', 'text', 'html', 'edited', 'user', 'unread',
            'read_by', 'urls', 'mentions', 'issues', 'meta', 'created_at', 'updated_at', 'room_id'];

        $this->attributes = (new AttributeMapper(json_decode($attributes, true)))
            ->rename('channel', 'room_id')
            ->value('user', function ($user) use($room) {
                return $room->client()->getUserById($user);
            })
            ->value('ts', function ($date) {
                return Carbon::createFromTimestamp($date)->setTimezone('Europe/Moscow');
            }, 'created_at')
            ->only($fields)
            ->toArray();

        if (!array_key_exists('room_id', $this->attributes)) {
            $this->attributes['room_id'] = $room->id();
        }

        $this->attributes['gitter_id'] = md5($room->id());
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }
}