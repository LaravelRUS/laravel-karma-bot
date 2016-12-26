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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function system()
    {
        return $this->belongsTo(System::class);
    }

    /**
     * @param Builder $builder
     * @param System $system
     * @return Builder
     */
    public static function scopeInSystem(Builder $builder, System $system)
    {
        return $builder->where('system_id', $system->id);
    }

    /**
     * @param Builder $builder
     * @param string $id
     * @return Builder
     */
    public static function scopeWithExternalId(Builder $builder, string $id)
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
