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
 * Class MultiChannel
 * @package KarmaBot\Bot
 */
class MultiChannel implements ConnectionInterface
{
    /**
     * @var string
     */
    private const ON_MESSAGE = 'message:%s';

    /**
     * @var Container
     */
    private $app;

    /**
     * @var EventBus[]
     */
    private $channels = [];

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * MultiChannel constructor.
     * @param Container $app
     * @param Channel[] ...$channels
     * @throws \InvalidArgumentException
     */
    public function __construct(Container $app, Channel ...$channels)
    {
        $this->app = $app;
        $this->events = new Dispatcher();

        foreach ($channels as $channel) {
            $bus = $channel->getChannelConnection($app);

            $bus->subscribe(function (AnswerInterface $answer) {
                $this->events->fire(sprintf(self::ON_MESSAGE, $answer->getChannel()->getId()), $answer);
            });
        }
    }

    /**
     * @param string $message
     */
    public function send(string $message): void
    {
        foreach ($this->channels as $channel) {
            $channel->publish($message);
        }
    }

    /**
     * @param \Closure $callback
     */
    public function subscribe(\Closure $callback): void
    {
        $this->events->listen(sprintf(self::ON_MESSAGE, '*'), $callback);
    }
}
