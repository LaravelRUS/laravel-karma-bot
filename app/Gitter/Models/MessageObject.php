<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 00:00
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Models;

use App\Gitter\Client;
use Carbon\Carbon;
use App\Gitter\Support\AttributeMapper;

/**
 * Class Message
 * @package App\Gitter\Models
 *
 * @property string $gitter_id
 * @property string $text
 * @property string $html
 * @property bool $edited
 * @property UserObject|\App\User $user
 * @property string $unread
 * @property int $read_by
 * @property array $urls
 * @property array $mentions
 * @property array $issues
 * @property array $meta
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class MessageObject extends Model
{
    /**
     * @param array $attributes
     * @return array
     * @throws \InvalidArgumentException
     */
    public function format(array $attributes): array
    {
        $fields = ['gitter_id', 'text', 'html', 'edited', 'user', 'unread',
            'read_by', 'urls', 'mentions', 'issues', 'meta', 'created_at', 'updated_at'];

        return (new AttributeMapper($attributes))

            ->rename('readBy', 'read_by')
            ->rename('id', 'gitter_id')
            ->value('editedAt', function($val)  { return !!$val;                            }, 'edited')
            ->value('fromUser', function($user) { return UserObject::findOrCreate($user);   }, 'user')
            ->value('sent',     function($date) { return new Carbon($date);                 }, 'created_at')
            ->value('editedAt', function($date) { return new Carbon($date);                 }, 'updated_at')

            ->only($fields)
            ->toArray();
    }
}
