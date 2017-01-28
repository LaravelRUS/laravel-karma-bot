<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Model\Transformer;

use Illuminate\Contracts\Container\Container;
use App\Model\Channel;
use App\Model\System;
use Serafim\KarmaCore\Io\ChannelInterface;

/**
 * Class SystemTransformer
 * @package KarmaBot\Model\Transformer
 * @mixin Channel
 */
trait ChannelTransformer
{
    /**
     * @param ChannelInterface $channel
     * @param System $system
     * @return static|ChannelTransformer|Channel
     * @throws \LogicException
     */
    public static function new(ChannelInterface $channel, System $system)
    {
        if (!$system->exists) {
            throw new \LogicException('System does not exists in the storage');
        }

        $model = new static();

        $model->system_id = $system->id;
        $model->sys_channel_id = $channel->getId();
        $model->name = $channel->getName();

        return $model;
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
