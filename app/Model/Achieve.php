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

/**
 * Class Achieve
 * @package KarmaBot\Model
 */
class Achieve extends Model
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
    protected $fillable = ['name', 'title', 'image', 'description'];

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_achievements');
    }
}
