<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package KarmaBot\Model
 */
class User extends Model
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $fillable = ['gitter_id', 'url', 'login', 'name', 'avatar'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function achievements()
    {
        return $this->belongsToMany(Achieve::class, 'user_achievements');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function karma()
    {
        return $this->hasMany(Karma::class, 'user_target_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function thanks()
    {
        return $this->hasMany(Karma::class, 'user_id', 'id');
    }
}
