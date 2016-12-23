<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class System
 * @package KarmaBot\Model
 */
class System extends Model
{
    /**
     * @var string
     */
    protected $table = 'systems';

    /**
     * @var array
     */
    protected $fillable = ['title', 'name', 'adapter', 'token', 'icon'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channels()
    {
        return $this->hasMany(Channel::class);
    }

    /**
     * @param Builder $builder
     * @param string $name
     * @return Builder
     */
    public static function scopeWithName(Builder $builder, string $name)
    {
        return $builder->where('name', $name);
    }
}
