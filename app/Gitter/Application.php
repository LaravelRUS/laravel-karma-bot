<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 19:55
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter;

use App;
use App\Gitter\Middleware\Storage;
use App\Gitter\Models\MessageObject;
use App\Gitter\Middleware\LoggerMiddleware;
use App\Gitter\Middleware\DbSyncMiddleware;
use Illuminate\Container\Container;

/**
 * Class Application
 * @package App\Gitter
 */
class Application
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Application constructor.
     * @param Container $container
     * @param $token
     */
    public function __construct(Container $container, $token)
    {
        $this->container = $container;
        $container->singleton(Client::class, function() use ($token) {
            return new Client($token);
        });
    }

    /**
     * @param $room
     * @throws \LogicException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function listenRoom($room)
    {
        $storage = new Storage($this->container);
        $storage->add(LoggerMiddleware::class, Storage::PRIORITY_MAXIMAL);
        $storage->add(DbSyncMiddleware::class, Storage::PRIORITY_MAXIMAL);


        $client = $this->container->make(Client::class);
        $client
            ->stream('messages', ['roomId' => $room])
            ->subscribe(function ($data) use ($storage) {
                $message = new MessageObject($data);
                $storage->handle($message);
            });

        $client->run();
    }
}
