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
     */
    public function format(array $attributes)
    {
        return [
            'gitter_id'  => $attributes['id'],
            'text'       => $attributes['text'],
            'html'       => $attributes['html'],
            'edited'     => !!$attributes['editedAt'],
            'user'       => User::findOrCreate($attributes['fromUser']),
            'unread'     => $attributes['unread'],
            'read_by'    => $attributes['readBy'],
            'urls'       => $attributes['urls'],
            'mentions'   => $attributes['mentions'],
            'issues'     => $attributes['issues'],
            'meta'       => $attributes['meta'],
            'created_at' => new Carbon($attributes['sent']),
            'updated_at' => new Carbon($attributes['editedAt'])
        ];
    }
}
