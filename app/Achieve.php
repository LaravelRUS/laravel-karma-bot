<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.10.2015 06:03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App;

use Carbon\Carbon;

/**
 * Class Achieve
 * @package App
 *
 * @property-read int $id
 * @property string $name
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property Carbon $created_at
 *
 */
class Achieve extends \Eloquent
{
    /**
     * @var string
     */
    protected $table = 'achievements';

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

        static::creating(function (Achieve $achieve) {
            $achieve->created_at = $achieve->freshTimestamp();

            if (static::has($achieve->user, $achieve->name)) {
                return false;
            }

            \Event::fire('achieve.add', ['achieve' => $achieve]);

            return null;
        });
    }

    /**
     * @param User $user
     * @param string $name
     * @return bool
     */
    public static function has(User $user, $name)
    {
        return !!static::query()
            ->where('user_id', $user->id)
            ->where('name', $name)
            ->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}