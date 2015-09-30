<?php
namespace App;
use App\Gitter\Client;
use App\Gitter\Models\Achieve;
use App\Gitter\Models\Room;
use App\Gitter\Models\UserObject;

/**
 * Class User
 * @package App
 */
class User extends \Eloquent
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
     * @TODO Add User::class memory pool (with GC probably)
     *
     * @param UserObject $userObject
     * @return UserObject|\Illuminate\Database\Eloquent\Model|null|static
     */
    public static function fromGitterObject(UserObject $userObject)
    {
        $user = static::where('gitter_id', $userObject->gitter_id)->first();
        if (!$user) {
            $user = static::create($userObject->toArray());
        }
        return $user;
    }

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
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
     * @param $title
     * @param $description
     * @param $icon
     */
    public function achieve($title, $description, $icon)
    {
        $room = \App::make(Room::class);

        $room->answer(new Achieve([
            'title'       => $title,
            'description' => $description,
            'image'       => $icon,
            'user'        => $this,
        ]));
    }
}
