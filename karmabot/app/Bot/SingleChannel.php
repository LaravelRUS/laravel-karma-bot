<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Bot;

use Illuminate\Contracts\Container\Container;
use Illuminate\Events\Dispatcher;
use KarmaBot\Model\Channel;
use Psr\Log\LoggerInterface;
use Serafim\KarmaCore\Io\ChannelInterface;
use Serafim\KarmaCore\Io\ReceivedMessageInterface;

/**
 * Class SingleChannel
 * @package KarmaBot\Bot
 */
class SingleChannel implements ConnectionInterface
{
    /**
     * @var ChannelInterface
     */
    private $io;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * Connection constructor.
     * @param Container $app
     * @param Channel $channel
     * @throws \InvalidArgumentException
     */
    public function __construct(Container $app, Channel $channel)
    {
        $this->events = new Dispatcher();
        $this->log = $app->make(LoggerInterface::class);
        $this->io = $channel->getChannelConnection($app);

        $this->io->subscribe(function (ReceivedMessageInterface $receivedMessage) {
            $this->events->fire('message', $receivedMessage);
            $this->onMessage($receivedMessage);
        });
    }

    /**
     * @param ReceivedMessageInterface $message
     */
    public function onMessage(ReceivedMessageInterface $message): void
    {

    }

    /**
     * @param string $message
     */
    public function send(string $message): void
    {
        $this->io->publish($message);
    }

    /**
     * @param \Closure $callback
     */
    public function subscribe(\Closure $callback): void
    {
        $this->events->listen('message', $callback);
    }

    /**
     * @param string $userId
     * @return bool
     */
    private function matchAuthUserId(string $userId): bool
    {
        return $this->io->getSystem()->auth()->getId() === $userId;
    }
}
