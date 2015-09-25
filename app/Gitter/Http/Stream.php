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
use React\HttpClient\Response;
use App\Gitter\Support\StreamBuffer;

/**
 * Class Stream
 * @package App\Gitter
 */
class Stream
{
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
     * @param Client $client
     * @param $route
     * @param array $args
     * @param string $method
     * @throws \InvalidArgumentException
     */
    public function __construct(Client $client, $route, array $args, $method = 'GET')
    {
        $this->url      = $client->getRouter()->route($route, $args);
        $this->method   = $method;
        $this->headers  = $client->getHeaders();
        $this->client   = $client;
        $this->buffer   = new StreamBuffer();


        $this->headers['Connection'] = 'Keep-Alive';

        $this->connect();
    }

    /**
     *
     */
    public function connect()
    {
        $request = $this->client
            ->getHttpClient()
            ->request($this->method, $this->url, $this->headers);

        $request->on('response', function (Response $response) {
            $response->on('data', function ($data, Response $response) {
                $this->buffer->add((string)$data);
            });
        });

        $request->on('end', function () {
            $this->buffer->clear();
        });

        $request->on('error', function() {
            $this->connect();
        });

        $request->end();
    }

    /**
     * @param callable $callback
     * @return Stream
     */
    public function subscribe(callable $callback): Stream
    {
        $this->buffer->subscribe(function ($message) use ($callback) {
            $message = trim($message);
            if ($message) {
                $data = json_decode(trim($message), true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $callback($data);
                }
            }
        });

        return $this;
    }
}
