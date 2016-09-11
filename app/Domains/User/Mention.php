<?php
declare(strict_types = 1);
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 28.03.2016 14:25
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Domains\User;

use Domains\Message\Message;

/**
 * Class Mention
 *
 * @package Domains\User
 * @property-read User $user
 * @property-read Message $message
 * @property string $id
 * @property string $message_id
 * @property string $user_id
 * @method static \Illuminate\Database\Query\Builder|\Domains\User\Mention whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Domains\User\Mention whereMessageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Domains\User\Mention whereUserId($value)
 * @mixin \Eloquent
 */
class Mention extends \Eloquent
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'mentions';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id', 'id');
    }
}
