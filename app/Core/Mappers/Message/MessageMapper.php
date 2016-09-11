<?php
declare(strict_types = 1);
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 28.03.2016 14:06
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Mappers\Message;

use Domains\Message\Message;
use Domains\Message\Relation;
use Domains\Message\Url;
use Domains\User\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Class Message
 *
 * @package Domains\Message
 * @property-read User $user
 * @property-read Url[]|Collection $urls
 * @property-read Message[]|Collection $answers
 * @property-read Message[]|Collection $questions
 * @property-read User[]|Collection $mentions
 * @method static Message|Builder|EloquentBuilder inHistoricalOrder()
 * @property string $id
 * @property string $gitter_id
 * @property string $room_id
 * @property string $user_id
 * @property string $text
 * @property string $text_rendered
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Core\Mappers\Message\MessageMapper whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Core\Mappers\Message\MessageMapper whereGitterId($value)
 * @method static \Illuminate\Database\Query\Builder|\Core\Mappers\Message\MessageMapper whereRoomId($value)
 * @method static \Illuminate\Database\Query\Builder|\Core\Mappers\Message\MessageMapper whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Core\Mappers\Message\MessageMapper whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\Core\Mappers\Message\MessageMapper whereTextRendered($value)
 * @method static \Illuminate\Database\Query\Builder|\Core\Mappers\Message\MessageMapper whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Core\Mappers\Message\MessageMapper whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MessageMapper extends Model
{
    /**
     * @var string
     */
    protected $table = 'messages';

    /**
     * @param EloquentBuilder $builder
     * @return $this
     */
    public static function scopeInHistoricalOrder(EloquentBuilder $builder)
    {
        return $builder->orderBy('created_at', 'asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mentions()
    {
        return $this->belongsToMany(User::class, 'mentions', 'message_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function answers()
    {
        return $this->belongsToMany(Message::class, 'message_relations', 'message_id', 'answer_id', Relation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function questions()
    {
        return $this->belongsToMany(Message::class, 'message_relations', 'answer_id', 'message_id', Relation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function urls()
    {
        return $this->hasMany(Url::class, 'message_id', 'id');
    }
}
