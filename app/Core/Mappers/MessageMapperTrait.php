<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.10.2015 16:59
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Mappers;

use Domains\Room;
use Domains\User;
use Domains\Message;
use Carbon\Carbon;
use App\Gitter\Support\AttributeMapper;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MessageMapperTrait
 * @package App\Mappers
 */
trait MessageMapperTrait
{
    /**
     * @param array $attributes
     * @return Message
     * @throws \InvalidArgumentException
     */
    public static function fromGitterObject(array $attributes)
    {
        $fields = ['gitter_id', 'text', 'html', 'edited', 'user', 'unread',
            'read_by', 'urls', 'mentions', 'issues', 'meta', 'created_at', 'updated_at', 'room_id'];

        $values = (new AttributeMapper($attributes))
            ->rename('readBy', 'read_by')
            ->rename('id', 'gitter_id')
            ->value('editedAt', function ($val) {
                return !!$val;
            }, 'edited')
            ->value('fromUser', function ($user) {
                return User::fromGitterObject($user);
            }, 'user')
            ->value('sent', function ($date) {
                return (new Carbon($date))->setTimezone('Europe/Moscow');
            }, 'created_at')
            ->value('editedAt', function ($date) {
                return (new Carbon($date))->setTimezone('Europe/Moscow');
            }, 'updated_at')
            ->value('mentions', function ($mentions) {
                return static::parseMentions($mentions);
            })
            ->only($fields)
            ->toArray();

        if (!array_key_exists('room_id', $values)) {
            $values['room_id'] = \App::make(Room::class)->id;
        }

        return static::unguarded(function () use ($values) {
            return new static($values);
        });
    }

    /**
     * @param array $inputMentions
     * @return array
     */
    protected static function parseMentions(array $inputMentions)
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
}
