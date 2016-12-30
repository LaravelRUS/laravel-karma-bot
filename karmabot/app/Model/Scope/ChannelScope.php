<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model\Scope;

use KarmaBot\Model\Channel;
use KarmaBot\Model\System;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ChannelScope
 * @package KarmaBot\Model\Scope
 * @mixin Channel
 */
trait ChannelScope
{
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
}
