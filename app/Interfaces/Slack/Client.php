<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @author butschster <butschster@gmail.com>
 * @date 24.09.2015 00:00
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Interfaces\Slack;

use Domains\Bot\ClientInterface;
use Domains\Message;
use Domains\Middleware\Storage;
use Domains\Room\RoomInterface;
use Domains\User;

/**
 * Class Client
 */
class Client implements ClientInterface
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var \React\EventLoop\ExtEventLoop|\React\EventLoop\LibEventLoop|\React\EventLoop\LibEvLoop|\React\EventLoop\StreamSelectLoop
     */
    protected $loop;

    /**
     * @var \Slack\RealTimeClient
     */
    protected $client;

    /**
     * Client constructor.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->loop = \React\EventLoop\Factory::create();
        $this->client = new \Slack\RealTimeClient($this->loop);

        $this->client->setToken($token);
    }

    /**
     * @param RoomInterface $room
     * @param string        $message
     *
     * @return void
     */
    public function sendMessage(RoomInterface $room, $message)
    {
        $this->client->getChannelById($room->id())->then(function (\Slack\Channel $channel) use($message) {
            $this->client->send((string) $message, $channel);
        });
    }

    /**
     * @param RoomInterface $room
     *
     * @return void
     */
    public function listen(RoomInterface $room)
    {
        $this->client->on('message', function ($data) use($room) {
            $this->onMessage(
                $room->middleware(),
                Message::unguarded(function() use($room, $data) {
                    return new Message(
                        (new MessageMapper($room, $data))->toArray()
                    );
                })
            );
        });

        $this->client->connect();
    }


    /**
     * @param Storage $middleware
     * @param Message $message
     */
    public function onMessage(Storage $middleware, Message $message)
    {
        try {
            $middleware->handle($message);
        } catch (\Exception $e) {
            $this->logException($e);
        }
    }

    protected function onClose()
    {
        $this->client->disconnect();
    }

    /**
     * @param \Exception $e
     */
    protected function onError(\Exception $e)
    {
        $this->logException($e);
    }
    /**
     * @return string
     */
    public function version()
    {
        return 'SlackBot v0.1b';
    }

    /**
     * @return ClientInterface
     */
    public function run(): ClientInterface
    {
        $this->loop->run();

        return $this;
    }

    /**
     * @param \Exception $e
     */
    protected function logException(\Exception $e)
    {
        \Log::error(
            $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n" .
            $e->getTraceAsString() . "\n" .
            str_repeat('=', 80) . "\n"
        );
    }

    /**
     * @param string $id
     *
     * @return User
     */
    public function getUserById($id)
    {
        $user = null;

        $this->client->getUserById($id)->then(function(\Slack\User $slackUser) use(&$user) {
            $user = UserMapper::fromSlackObject(
                $slackUser
            );
        });

        return $user;
    }
}