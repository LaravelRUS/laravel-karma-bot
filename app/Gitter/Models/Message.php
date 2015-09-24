<?php
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