<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Bot;

use Evenement\EventEmitterTrait;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Events\Dispatcher;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use KarmaBot\Bot\Middleware\Manager;
use KarmaBot\Bot\Middleware\MiddlewareInterface;
use KarmaBot\Model\Channel;
use KarmaBot\Model\Middleware;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use Serafim\KarmaCore\Io\ChannelInterface;
use Serafim\KarmaCore\Io\ReceivedMessageInterface;

/**
 * Class SingleChannel
 * @package KarmaBot\Bot
 */
class SingleChannel implements ConnectionInterface
{
    /**
     * @var MiddlewareInterface[]|Collection
     */
    private $middleware;

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
        $manager = $app->make(Manager::class);

        $this->middleware = $this->createMiddleware($manager, $channel);
        $this->events = new Dispatcher();
        $this->log = $app->make(LoggerInterface::class);
        $this->io = $channel->getChannelConnection($app);

        $this->io->subscribe(function (ReceivedMessageInterface $receivedMessage) {
            $this->events->fire('message', $receivedMessage);
            $this->onMessage($receivedMessage);
        });
    }

    /**
     * @param ReceivedMessageInterface $receivedMessage
     * @return \Closure
     */
    private function filter(ReceivedMessageInterface $receivedMessage): \Closure
    {
        return function (MiddlewareInterface $middleware) use ($receivedMessage) {
            switch ($middleware->getFlowType()) {
                case MiddlewareInterface::FLOW_LOGIC_SKIP_SELF:
                    return !$this->matchAuthUserId($receivedMessage->getUser()->getId());
            }

            return true;
        };
    }

    /**
     * @param string $userId
     * @return bool
     */
    private function matchAuthUserId(string $userId): bool
    {
        return $this->io->getSystem()->auth()->getId() === $userId;
    }

    /**
     * @param Manager $manager
     * @param Channel $channel
     * @return Collection
     */
    private function createMiddleware(Manager $manager, Channel $channel): Collection
    {
        $list = new Collection();

        /** @var Middleware $model */
        foreach ($channel->middleware as $model) {
            $middleware = $manager->make($model->name, $model->options);

            $list->push($middleware);
        }

        return $list;
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
     * @param ReceivedMessageInterface $message
     */
    public function onMessage(ReceivedMessageInterface $message): void
    {
        $list = $this->middleware->filter(function (MiddlewareInterface $middleware) use ($message) {
            return $this->filter($message);
        });

        $response = (new Pipeline())
            ->send($message)
            ->through($list->toArray())
            ->via('handle')
            ->then(function (ReceivedMessageInterface $message) {
                return null;
            });

        if (is_string($response)) {
            $this->send($response);
        }
    }
}
