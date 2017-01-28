<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Model\Scope\UserScope;
use App\Model\Transformer\UserTransformer;

/**
 * Class User
 * @package KarmaBot\Model
 */
class User extends Model
{
    use UserScope;
    use UserTransformer;

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
     * @return BelongsToMany
     */
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achieve::class, 'user_achievements');
    }

    /**
     * @return HasMany
     */
    public function karma(): HasMany
    {
        return $this->hasMany(Karma::class, 'from_user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function thanks(): HasMany
    {
        return $this->hasMany(Karma::class, 'to_user_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function systems()
    {
        return $this->belongsToMany(System::class, 'user_system');
    }
}
