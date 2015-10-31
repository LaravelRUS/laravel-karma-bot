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
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Class Request
 * @package App\Gitter
 */
class Request
{
    /**
     * @var array
     */
    protected $callbacks = [];

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
    }

    /**
     * @param null $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function send($data = null)
    {
        return (new Guzzle)->request($this->method, $this->url, [
            'verify'  => false,
            'headers' => $this->headers,

            'body' => is_array($data) || is_object($data)
                ? json_encode($data)
                : $data,
        ]);
    }

    /**
     * @param null $data
     * @return array
     */
    public function sendParseJson($data = null): array
    {
        return json_decode($this->send($data)->getBody(), true);
    }

    /**
     * @param callable $callback
     * @return Request
     */
    public function subscribe(callable $callback): Request
    {
        $this->callbacks[] = $callback;

        return $this;
    }
}
