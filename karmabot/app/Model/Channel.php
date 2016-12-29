<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Serafim\KarmaCore\Io\ChannelInterface;

/**
 * Class Channel
 * @package KarmaBot\Model
 */
class Channel extends Model
{
    /**
     * @var string
     */
    protected $table = 'channels';

    /**
     * @var array
     */
    protected $fillable = ['system_id', 'sys_channel_id', 'name'];

    /**
     * @return BelongsTo
     */
    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    /**
     * @param Builder $builder
     * @param System $system
     * @return Builder
     */
    public static function scopeInSystem(Builder $builder, System $system): Builder
    {
        return $builder->where('system_id', $system->id);
    }

    /**
     * @param Builder $builder
     * @param string $id
     * @return Builder
     */
    public static function scopeWithExternalId(Builder $builder, string $id): Builder
    {
        return $builder->where('sys_channel_id', $id);
    }

    /**
     * @param Container $container
     * @return ChannelInterface
     * @throws \InvalidArgumentException
     */
    public function getChannelConnection(Container $container): ChannelInterface
    {
        return $this->system
            ->getSystemConnection($container)
            ->channel($this->sys_channel_id);
    }
}
