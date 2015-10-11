<?php
namespace App;

use Carbon\Carbon;
use App\Mappers\UserMapperTrait;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Class User
 * @package App
 *
 * @property int $id
 * @property string $gitter_id
 * @property string $name
 * @property string $avatar
 * @property string $url
 * @property string $login
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * === Accessors ===
 *
 * @property-read string $karma_text
 * @property-read int $karma
 * @property-read int $thanks
 * @property-read Carbon $last_karma_time
 *
 */
class User extends \Eloquent implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable,
        Authorizable,
        CanResetPassword,

        // Gitter converter
        UserMapperTrait;

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
     *
     */
    protected static function boot()
    {
        parent::boot();
    }

    /**
     * @return mixed
     */
    public function getKarmaAttribute()
    {
        $query = Karma::query()->where('user_target_id', $this->id);

        return
            $query->where('status', Karma::STATUS_INCREMENT)->count() -
            $query->where('status', Karma::STATUS_DECREMENT)->count();
    }

    /**
     * @return mixed
     */
    public function getThanksAttribute()
    {
        return Karma::query()
            ->where('user_id', $this->id)
            ->where('status', Karma::STATUS_INCREMENT)
            ->count();

    }

    /**
     * @return Carbon
     */
    public function getLastKarmaTimeAttribute()
    {
        $result = Karma::query()
            ->where('user_target_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->take(1)
            ->first();

        if ($result) {
            return $result->created_at;
        }

        return Carbon::createFromTimestamp(0);
    }

    /**
     * @return string
     */
    public function getKarmaTextAttribute()
    {
        return ($this->karma > 0 ? '+' : '') .
        $this->karma;
    }

    /**
     * @param User $user
     * @param Message $message
     * @return static
     */
    public function addKarmaTo(User $user, Message $message)
    {
        return Karma::create([
            'room_id'        => \App::make(Room::class)->id,
            'message_id'     => $message->gitter_id,
            'user_id'        => $this->id,
            'user_target_id' => $user->id,
            'status'         => Karma::STATUS_INCREMENT,
        ]);
    }
}
