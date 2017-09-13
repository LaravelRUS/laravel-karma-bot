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
namespace Interfaces\Gitter;


use Carbon\Carbon;
use Domains\User;
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
    public function __construct(RoomInterface $room, array $attributes)
    {
        $fields = ['gitter_id', 'text', 'html', 'edited', 'user', 'unread',
            'read_by', 'urls', 'mentions', 'issues', 'meta', 'created_at', 'updated_at', 'room_id'];

        $this->attributes = (new AttributeMapper($attributes))
            ->rename('readBy', 'read_by')
            ->rename('id', 'gitter_id')
            ->value('editedAt', function ($val) {
                return !!$val;
            }, 'edited')
            ->value('fromUser', function ($user) {
                return UserMapper::fromGitterObject($user);
            }, 'user')
            ->value('sent', function ($date) {
                return (new Carbon($date))->setTimezone('Europe/Moscow');
            }, 'created_at')
            ->value('editedAt', function ($date) {
                return (new Carbon($date))->setTimezone('Europe/Moscow');
            }, 'updated_at')
            ->value('mentions', function ($mentions) {
                return $this->parseMentions($mentions);
            })
            ->only($fields)
            ->toArray();

        if (!array_key_exists('room_id', $this->attributes)) {
            $this->attributes['room_id'] = $room->id();
        }
    }

    /**
     * @param array $inputMentions
     * @return array
     */
    protected function parseMentions(array $inputMentions)
    {
        $ids = [];

        $mentions = [];

        foreach ($inputMentions as $mention) {
            if (array_key_exists('userId', $mention)) {
                $user = User::where('gitter_id', $mention['userId'])->first();

                if ($user && !in_array($user->gitter_id, $ids, false)) {
                    $ids[] = $user->gitter_id;
                    $mentions[] = $user;
                }
            }
        }

        return $mentions;
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