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

use App\Gitter\Support\AttributeMapper;
use Carbon\Carbon;

/**
 * Class Message
 * @package App\Gitter\Models
 */
class Message extends Model
{
    /**
     * @param array $attributes
     * @return array
     * @throws \InvalidArgumentException
     */
    public function format(array $attributes): array
    {
        $fields = ['id', 'text', 'html', 'edited', 'user', 'unread',
            'read_by', 'urls', 'mentions', 'issues', 'meta', 'created_at', 'updated_at'];

        return (new AttributeMapper($attributes))

            ->value('editedAt', function($val)  { return !!$val;                    }, 'edited')
            ->value('fromUser', function($user) { return User::findOrCreate($user); }, 'user')
            ->rename('readBy', 'read_by')
            ->value('sent',     function($date) { return new Carbon($date);         }, 'created_at')
            ->value('editedAt', function($date) { return new Carbon($date);         }, 'updated_at')

            ->only($fields)
            ->toArray();

    }
}
