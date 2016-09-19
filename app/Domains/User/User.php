<?php
namespace App\Domains\User;

use App\Domains\Karma\Karma;
use App\Domains\Achieve\Achieve;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App\Domains\User
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function achievements()
    {
        return $this->hasMany(Achieve::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function karma()
    {
        return $this->hasMany(Karma::class, 'user_target_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thanks()
    {
        return $this->hasMany(Karma::class, 'user_id', 'id');
    }
}