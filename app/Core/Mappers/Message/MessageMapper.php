<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 13:58
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Mappers\Message;

use Carbon\Carbon;
use Core\Observers\IdObserver;
use Domains\Karma\Karma;
use Domains\Message\Text;
use Domains\Room\Room;
use Domains\User\Mention;
use Domains\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

/**
 * Class MessageMapper
 * @package Core\Mappers\Message
 *
 * @property-read string $id
 * @property-read Text $text
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Room $room
 * @property-read Mention[]|Collection $mentions
 * @property-read Karma[]|Collection $karma
 */
class MessageMapper extends Model
{
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'messages';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'id'   => 'string',
        'text' => 'string',
    ];

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::observe(new IdObserver());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Relation
     */
    public function user() : Relation
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Relation
     */
    public function room() : Relation
    {
        return $this->belongsTo(Room::class, 'id', 'room_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Relation
     */
    public function mentions() : Relation
    {
        return $this->hasMany(Mention::class, 'message_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Relation
     */
    public function karma() : Relation
    {
        return $this->hasMany(Karma::class, 'message_id', 'id');
    }

    /**
     * @param string $text
     * @return Text
     */
    public function getTextAttribute(string $text) : Text
    {
        return new Text($text);
    }
}