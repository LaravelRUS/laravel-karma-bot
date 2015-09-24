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

namespace App\Gitter;

use App\Gitter\Http\Stream;
use App\Gitter\Http\UrlStorage;
use React\EventLoop\Factory as EventLoop;
use React\HttpClient\Client as ReactClient;
use React\HttpClient\Factory as HttpClient;
use React\Dns\Resolver\Factory as DnsResolver;

/**
 * Class Client
 * @package App\Gitter
 */
class Client
{
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
     * @param $token
     */
    public function __construct($token)
    {
        $this->token        = $token;
        $this->loop         = EventLoop::create();
        $this->dnsResolver  = (new DnsResolver())->createCached('8.8.8.8', $this->loop);
        $this->client       = (new HttpClient())->create($this->loop, $this->dnsResolver);
        $this->urlStorage   = new UrlStorage($token);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
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
     * @return Client
     */
    public function run(): Client
    {
        $this->loop->run();
        return $this;
    }
}
