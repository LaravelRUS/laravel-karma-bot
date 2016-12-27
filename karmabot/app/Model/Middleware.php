<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Middleware
 * @package KarmaBot\Model
 */
class Middleware extends Model
{
    /**
     * @var string
     */
    protected $table = 'middleware';

    /**
     * @var array
     */
    protected $fillable = ['name', 'options', 'channel_id', 'priority'];

    /**
     * @return BelongsTo
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * @param array|null $value
     * @return array
     */
    public function getOptionsAttribute($value): array
    {
        if ($value === null) {
            return [];
        }

        return (array)json_decode($value, true);
    }

    /**
     * @param $value
     */
    public function setOptionsAttribute($value): void
    {
        $this->attributes['options'] = is_string($value) ? $value : json_encode($value);
    }
}
