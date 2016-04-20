<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 13:37
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Mappers\User;

use Core\Observers\IdObserver;
use Domains\Message\Message;
use Domains\User\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MentionMapper
 * @package Core\Mappers\User

 * @property-read string $id
 *
 * @property-read User $creator
 * @property-read User $target
 * @property-read Message $message
 */
class MentionMapper extends Model
{
    /**
     * @var string
     */
    protected $table = 'mentions';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::observe(new IdObserver());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(User::class, 'id', 'user_target_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class, 'id', 'message_id');
    }
}