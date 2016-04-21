<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 13:47
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Mappers\RoomMapper;

use Core\Observers\IdObserver;
use Domains\Karma\Karma;
use Domains\Message\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class RoomMapper
 * @package Core\Mappers\RoomMapper
 *
 * @property-read string $id
 * @property-read string $url
 * @property-read string $title
 *
 * @property-read Message[]|Collection $messages
 * @property-read Karma[]|Collection $karma
 */
class RoomMapper extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'rooms';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'id'    => 'string',
        'url'   => 'string',
        'title' => 'string',
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'room_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function karma()
    {
        return $this->hasMany(Karma::class, 'room_id', 'id');
    }
}