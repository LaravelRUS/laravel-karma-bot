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
namespace Domains;

use Carbon\Carbon;
use Interfaces\Gitter\Achieve\AbstractAchieve;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Achieve
 *
 * @deprecated 
 * @property-read int $id
 * @property string $name
 * @property int $user_id
 * 
 * === Relations ===
 * @property-read User $user
 * 
 * === Accessors ===
 * @property-read string $title
 * @property-read string $description
 * @property-read string $image
 * @property string $created_at
 * @property-read AbstractAchieve $achieve
 * @method static \Illuminate\Database\Query\Builder|\Domains\Achieve whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Domains\Achieve whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Domains\Achieve whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Domains\Achieve whereCreatedAt($value)
 * @mixin \Eloquent
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
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $appends = ['title', 'description', 'image'];

    /**
     * @var AbstractAchieve
     */
    protected $cachedAchieve = null;

    /**
     * Boot
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function (Achieve $achieve) {
            if (!$achieve->created_at) {
                $achieve->created_at = $achieve->freshTimestamp();
            }

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

    /**
     * @return AbstractAchieve
     */
    public function getAchieveAttribute()
    {
        if ($this->cachedAchieve === null) {
            $this->cachedAchieve = new $this->name;
        }

        return $this->cachedAchieve;
    }

    /**
     * @return string
     */
    public function getTitleAttribute()
    {
        return $this->achieve->title;
    }

    /**
     * @return string
     */
    public function getDescriptionAttribute()
    {
        return $this->achieve->description;
    }

    /**
     * @return string
     */
    public function getImageAttribute()
    {
        return $this->achieve->image;
    }

    /**
     * @param $time
     * @return Carbon
     */
    public function getCreatedAtAttribute($time)
    {
        return new class($time) extends Carbon implements Arrayable
        {
            public function toArray()
            {
                return [
                    'date'     => $this->toIso8601String(),
                    'timezone' => $this->timezone,
                ];
            }
        };
    }
}