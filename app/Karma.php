<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.10.2015 20:15
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App;

use Carbon\Carbon;

/**
 * Class Karma
 * @package App
 *
 * @property-read int $id
 * @property string $room_id
 * @property string $message_id
 * @property int $user_id
 * @property int $user_target_id
 * @property string $status
 * @property Carbon $created_at
 *
 * === Relations ===
 *
 * @property-read User $user
 * @property-read User $target
 *
 */
class Karma extends \Eloquent
{
    const STATUS_INCREMENT = 'inc';
    const STATUS_DECREMENT = 'dec';

    /**
     * @var string
     */
    protected $table = 'karma';

    /**
     * @var array
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at'];

    /**
     * Boot
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function (Karma $karma) {
            $karma->created_at = $karma->freshTimestamp();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(User::class, 'user_target_id', 'id');
    }
}
