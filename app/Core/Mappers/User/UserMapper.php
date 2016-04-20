<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 13:22
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Mappers\User;

use Carbon\Carbon;
use Core\Observers\IdObserver;
use Domains\Achieve\Achieve;
use Domains\Karma\Karma;
use Domains\Message\Message;
use Domains\User\Mention;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class UserMapper
 * @package Core\Mappers\User
 *
 * @property-read string $id
 * @property-read string $name
 * @property-read string $login
 * @property-read string $avatar
 * @property-read string $email
 * @property-read string $password
 * @property-read string $remember_token
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * @property-read Achieve[]|Collection $achievements
 * @property-read Message[]|Collection $messages
 * @property-read Karma[]|Collection $karma
 * @property-read Karma[]|Collection $thanks
 * @property-read Mention[]|Collection $mentions
 * @property-read Mention[]|Collection $mentioned
 */
class UserMapper extends Model
{
    /**
     * @var string
     */
    protected $table = 'users';

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function achievements()
    {
        return $this->hasMany(Achieve::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id', 'id');
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mentions()
    {
        return $this->hasMany(Mention::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mentioned()
    {
        return $this->hasMany(Mention::class, 'user_target_id', 'id');
    }
}