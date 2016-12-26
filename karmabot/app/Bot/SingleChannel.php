<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Bot;

use Serafim\KarmaCore\Io\AnswerInterface;
use Serafim\KarmaCore\Io\ChannelInterface as EventBus;
use Illuminate\Contracts\Container\Container;
use Illuminate\Events\Dispatcher;
use KarmaBot\Model\Channel;

/**
 * Class SingleChannel
 * @package KarmaBot\Bot
 */
class SingleChannel implements ConnectionInterface
{
    /**
     * @var string
     */
    private const ON_MESSAGE = 'message';

    /**
     * @var Container
     */
    private $app;

    /**
     * @var EventBus
     */
    private $channel;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * Connection constructor.
     * @param Container $app
     * @param Channel $channel
     * @throws \InvalidArgumentException
     */
    public function __construct(Container $app, Channel $channel)
    {
        $this->app = $app;
        $this->channel = $channel->getChannelConnection($app);
        $this->events = new Dispatcher();

        $this->channel->subscribe(function (AnswerInterface $answer) {
            $this->events->fire(self::ON_MESSAGE, $answer);
        });
    }

    /**
     * @param string $message
     */
    public function send(string $message): void
    {
        $this->channel->publish($message);
    }

    /**
     * @param \Closure $callback
     */
    public function subscribe(\Closure $callback): void
    {
        $this->events->listen(self::ON_MESSAGE, $callback);
    }
}
