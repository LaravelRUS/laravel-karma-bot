<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 14:13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Mappers\Karma;

use Carbon\Carbon;
use Core\Observers\IdObserver;
use Domains\Message\Message;
use Domains\Room\Room;
use Domains\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class KarmaMapper
 * @package Core\Mappers\Karma
 *
 * @property-read string $id
 * @property-read Carbon $created_at
 *
 * @property-read Room $room
 * @property-read Message $message
 * @property-read User $user
 * @property-read User $target
 */
class KarmaMapper extends Model
{
    /**
     * @var array
     */
    public $timestamps = ['created_at'];

    /**
     * @var string
     */
    protected $table = 'karma';

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
    public function room() : Relation
    {
        return $this->belongsTo(Room::class, 'id', 'room_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Relation
     */
    public function message() : Relation
    {
        return $this->belongsTo(Message::class, 'id', 'message_id');
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
    public function target() : Relation
    {
        return $this->belongsTo(User::class, 'id', 'user_target_id');
    }
}