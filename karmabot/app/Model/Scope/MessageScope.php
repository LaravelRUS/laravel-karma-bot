<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model\Scope;

use Illuminate\Database\Eloquent\Builder;
use KarmaBot\Model\Channel;
use KarmaBot\Model\User;
use Serafim\KarmaCore\Io\ReceivedMessageInterface;

/**
 * Class MessageScope
 * @package KarmaBot\Model\Scope
 */
trait MessageScope
{
    /**
     * @param Builder $builder
     * @param ReceivedMessageInterface $message
     * @param Channel $channel
     * @param User $user
     * @return MessageScope|Builder|\Illuminate\Database\Query\Builder
     */
    public static function scopeWhereExternalMessage(
        Builder $builder,
        ReceivedMessageInterface $message,
        Channel $channel,
        User $user
    )
    {
        return $builder
            ->where('sys_message_id', $message->id())
            ->where('channel_id', $channel->id)
            ->where('user_id', $user->id);
    }
}
