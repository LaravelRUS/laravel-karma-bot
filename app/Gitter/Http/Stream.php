<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 00:00
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Http;

use App\Gitter\Client;
use Carbon\Carbon;
use Illuminate\Events\Dispatcher;
use React\HttpClient\Response;
use App\Gitter\Support\StreamBuffer;

/**
 * Class Stream
 * @package App\Gitter
 */
class Stream
{
    // Message part (chunk)
    const EVENT_CHUNK = 'chunk';
    // Full message string
    const EVENT_DATA = 'data';
    // Parsed as json data
    const EVENT_MESSAGE = 'message';
    // Connection
    const EVENT_CONNECT = 'connect';
    // Errors
    const EVENT_ERROR = 'error';
    // End
    const EVENT_END = 'end';

    /**
     * @var StreamBuffer
     */
    protected $buffer;

    /**
     * @var string|mixed|string
     */
    protected $url;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Dispatcher|null
     */
    protected $events = null;

    /**
     * @param Client $client
     * @param $route
     * @param array $args
     * @param string $method
     * @throws \InvalidArgumentException
     */
    public function __construct(Client $client, $route, array $args, $method = 'GET')
    {
        $this->url = $client->getRouter()->route($route, $args);
        $this->method = $method;
        $this->headers = $client->getHeaders();
        $this->client = $client;
        $this->buffer = new StreamBuffer();
        $this->events = new Dispatcher();

        $this->headers['Connection'] = 'Keep-Alive';

        $this->buffer->subscribe(function ($message) {
            $message = trim($message);

            if ($message) {
                $this->events->fire(static::EVENT_DATA, [$message]);

                $data = json_decode(trim($message), true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->events->fire(static::EVENT_MESSAGE, [$this, $data]);

                } else {
                    $this->events->fire(static::EVENT_ERROR, [
                        $this,
                        new \LogicException(json_last_error_msg()),
                    ]);
                }
            }
        });

        $this->connect();
    }

    /**
     * @return \React\HttpClient\Request
     */
    public function connect()
    {
        $request = $this->client
            ->getHttpClient()
            ->request($this->method, $this->url, $this->headers);

        $request->on('response', function (Response $response) {
            $response->on('data', function ($data, Response $response) {
                $data = (string)$data;
                $this->events->fire(static::EVENT_CHUNK, [$this, $data, $response]);
                $this->buffer->add($data);
            });
        });

        $request->on('end', function () {
            $this->buffer->clear();
            $this->events->fire(static::EVENT_END, [$this]);
        });

        $request->on('error', function ($exception) {
            $this->events->fire(static::EVENT_ERROR, [$this, $exception]);
        });

        $this->events->fire(static::EVENT_CONNECT, [$this, $request]);

        $request->end();

        return $request;
    }

    /**
     * @return \React\HttpClient\Request
     */
    public function reconnect()
    {
        return $this->connect();
    }

    /**
     * @deprecated
     * @param callable $callback
     * @return Stream
     */
    public function subscribe(callable $callback): Stream
    {
        $this->on(static::EVENT_MESSAGE, $callback);

        return $this;
    }

    /**
     * @param string|array $events
     * @param $listener
     * @param int $priority
     * @return $this
     */
    public function on($events, $listener, $priority = 0)
    {
        $this->events->listen($events, $listener, $priority);

        return $this;
    }
}
