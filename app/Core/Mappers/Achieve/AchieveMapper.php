<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 14:20
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Mappers\Achieve;

use Carbon\Carbon;
use Core\Observers\IdObserver;
use Domains\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class AchieveMapper
 * @package Core\Mappers\Achieve
 * @property-read string $id
 * @property-read string $name
 * @property-read Carbon $created_at
 *
 * @property-read User $user
 */
class AchieveMapper extends Model
{
    /**
     * @var string
     */
    protected $table = 'achievements';

    /**
     * @var array
     */
    public $timestamps = [ 'created_at' ];

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
}