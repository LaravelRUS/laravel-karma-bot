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
     * @param Client $client
     * @param $route
     * @param array $args
     * @param string $method
     * @throws \InvalidArgumentException
     */
    public function __construct(Client $client, $route, array $args, $method = 'GET')
    {
        $router = $client->getRouter();
        $url = $router->route($route, $args);


        $this->buffer = new StreamBuffer();

        $headers = $client->getHeaders();
        $headers['Connection'] = 'Keep-Alive';

        $request = $client
            ->getHttpClient()
            ->request($method, $url, $headers);

        $request->on('response', function (Response $response) {
            $response->on('data', function ($data, Response $response) {
                $this->buffer->add((string)$data);
            });
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
            $data = json_decode(trim($message), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $callback($data);
            }
        });

        return $this;
    }
}
