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

namespace Interfaces\Gitter;

use Domains\Bot\ClientInterface;
use Domains\Bot\TextParserInterface;
use Domains\User;
use Domains\Message;
use Interfaces\Gitter\Http\Stream;
use Interfaces\Gitter\Http\Request;
use Domains\Middleware\Storage;
use Domains\Room\RoomInterface;
use InvalidArgumentException;
use Interfaces\Gitter\Http\UrlStorage;
use React\EventLoop\Factory as EventLoop;
use React\HttpClient\Client as ReactClient;
use React\HttpClient\Factory as HttpClient;
use React\Dns\Resolver\Factory as DnsResolver;

/**
 * Class Client
 */
class Client implements ClientInterface
{
    const VERSION = 'KarmaBot for Gitter 0.1b';

    /**
     * @var string
     */
    protected $token;

    /**
     * @var \React\HttpClient\Client
     */
    protected $client;

    /**
     * @var \React\EventLoop\ExtEventLoop|\React\EventLoop\LibEventLoop|\React\EventLoop\LibEvLoop|\React\EventLoop\StreamSelectLoop
     */
    protected $loop;

    /**
     * @var \React\Dns\Resolver\Resolver
     */
    protected $dnsResolver;

    /**
     * @var UrlStorage
     */
    protected $urlStorage;

    /**
     * @var string
     */
    protected $room;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var TextParserInterface
     */
    protected $parser;

    /**
     * Client constructor.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->loop = EventLoop::create();
        $this->dnsResolver = (new DnsResolver())->createCached('8.8.8.8', $this->loop);
        $this->client = (new HttpClient())->create($this->loop, $this->dnsResolver);
        $this->urlStorage = (new UrlStorage($token));

        $this->authAs(null);
        $this->parser = new TextParser('');
    }

    /**
     * @param null|string $gitterUserId
     * @return $this
     * @throws InvalidArgumentException
     */
    public function authAs($gitterUserId = null)
    {
        $this->user = $this->getUserById($gitterUserId);

        \Auth::loginUsingId($this->user->id);

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthUser()
    {
        return $this->user;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ];
    }

    /**
     * @return ReactClient
     */
    public function getHttpClient(): ReactClient
    {
        return $this->client;
    }

    /**
     * @return UrlStorage
     */
    public function getRouter(): UrlStorage
    {
        return $this->urlStorage;
    }

    /**
     * @return \React\EventLoop\ExtEventLoop|\React\EventLoop\LibEventLoop|\React\EventLoop\LibEvLoop|\React\EventLoop\StreamSelectLoop
     */
    public function getEventLoop()
    {
        return $this->loop;
    }

    /**
     * @param $route
     * @param array $args
     * @param string $method
     * @return Stream
     * @throws \InvalidArgumentException
     */
    public function stream($route, array $args = [], $method = 'GET'): Stream
    {
        return new Stream($this, $route, $args, $method);
    }

    /**
     * @param $route
     * @param array $args
     * @param null $content
     * @param string $method
     * @return array
     * @throws \InvalidArgumentException
     */
    public function request($route, array $args = [], $content = null, $method = 'GET')
    {
        try {
            return (new Request($this, $route, $args, $method))
                ->sendParseJson($content);

        } catch (\Exception $e) {
            return $this->request($route, $args, $content, $method);
        }
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
     * @param RoomInterface $room
     */
    public function listen(RoomInterface $room)
    {
        $this
            ->stream('messages', ['roomId' => $room->id()])
            ->on(Stream::EVENT_MESSAGE, function ($stream, $data) use($room) {
                $this->onMessage(
                    $room->middleware(),
                    Message::unguarded(function() use($room, $data) {
                        return new Message(
                            (new MessageMapper($room, $data))->toArray(),
                            $room
                        );
                    })
                );
            })
            ->on(Stream::EVENT_END, [$this, 'onClose'])
            ->on(Stream::EVENT_ERROR, [$this, 'onError']);
    }

    /**
     * @return string
     */
    public function version()
    {
        return static::VERSION;
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

    /**
     * @TODO I do not know if it works
     * @param Stream $stream
     */
    protected function onClose(Stream $stream)
    {
        $stream->reconnect();
    }

    /**
     * @param Stream $stream
     * @param \Exception $e
     */
    protected function onError(Stream $stream, \Exception $e)
    {
        $this->logException($e);
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
     * @param RoomInterface $room
     * @param string        $message
     */
    public function sendMessage(RoomInterface $room, $message)
    {
        $this->request('message.send', ['roomId' => $room->id()], [
            'text' => (string) $this->parser->parse($message),
        ], 'POST');
    }

    /**
     * @param string $id
     *
     * @return User
     */
    public function getUserById($id)
    {
        return UserMapper::fromGitterObject(
            $this->request('user', ['userId' => $id])[0]
        );
    }
}
