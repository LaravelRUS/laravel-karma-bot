<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use KarmaBot\Model\System\DriversMap;
use KarmaBot\Model\Transformer\SystemTransformer;
use Serafim\KarmaCore\Io\UserInterface;

/**
 * Class System
 * @package KarmaBot\Model
 *
 * @property string $driver_class
 */
class System extends Model
{
    use SystemTransformer;

    /**
     * @var string
     */
    protected $table = 'systems';

    /**
     * @var array
     */
    protected $fillable = ['title', 'name', 'adapter', 'token', 'icon'];

    /**
     * @var array
     */
    protected $appends = ['driver_class'];

    /**
     * @return HasMany
     */
    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDriverClassAttribute(): string
    {
        return DriversMap::getDriverByAlias($this->driver);
    }

    /**
     * @param string $class
     */
    public function setDriverClassAttribute(string $class): void
    {
        $this->attributes['driver'] = DriversMap::findAliasByDriver($class);
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_system');
    }

    /**
     * @param User $user
     * @param string $sysUserId
     * @return Model
     */
    public function addUser(User $user, string $sysUserId)
    {
        return $this->users()->save($user, ['sys_user_id' => $sysUserId]);
    }
}
