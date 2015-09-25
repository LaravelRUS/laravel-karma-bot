<?php
namespace App;
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
}
