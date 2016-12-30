<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model\Transformer;

use KarmaBot\Model\Channel;
use KarmaBot\Model\Message;
use KarmaBot\Model\User;
use Serafim\KarmaCore\Io\ReceivedMessageInterface;

/**
 * Class MessageTransformer
 * @package KarmaBot\Model\Transformer
 * @mixin Message
 */
trait MessageTransformer
{
    /**
     * @param ReceivedMessageInterface $message
     * @param Channel $channel
     * @param User $user
     * @return Message|MessageTransformer|static
     * @throws \LogicException
     */
    public static function new(ReceivedMessageInterface $message, Channel $channel, User $user): Message
    {
        if (!$channel->exists) {
            throw new \LogicException('Channel does not exists in the storage');
        }

        if (!$user->exists) {
            throw new \LogicException('User does not exists in the storage');
        }

        $model = new static();

        $model->channel_id = $channel->id;
        $model->user_id = $user->id;
        $model->sys_message_id = $message->id();
        $model->body = $message->getBody();
        $model->created_at = $message->at();

        return $model;
    }

    /**
     * @param ReceivedMessageInterface $message
     * @param Channel $channel
     * @return bool
     * @throws \LogicException
     */
    public static function stored(ReceivedMessageInterface $message, Channel $channel): bool
    {
        if (!$channel->exists) {
            throw new \LogicException('Channel does not exists in the storage');
        }

        return static::where('sys_message_id', $message->id())
                ->where('channel_id', $channel->id)
                ->first() !== null;
    }
}
