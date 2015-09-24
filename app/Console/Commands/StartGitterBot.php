<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 15:27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands;


use App\Gitter\Thread;
use App\Gitter\Client;
use App\Gitter\Threads\Worker;
use App\Gitter\Models\Message;
use Illuminate\Console\Command;
use App\Gitter\Middleware\Storage;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use App\Gitter\Middleware\LoggerMiddleware;
use App\Gitter\Middleware\DbSyncMiddleware;

/**
 * Class StartGitterBot
 * @package App\Console\Commands
 */
class StartGitterBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitter:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Startup gitter bot';

    /**
     * Execute the console command.
     *
     * @param Container $container
     * @param Repository $config
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function handle(Container $container, Repository $config)
    {
        $token      = $config->get('gitter.token');
        $rooms      = $config->get('gitter.rooms');

        $client     = new Client($token);


        $storage    = new Storage($container);
        $storage->add(LoggerMiddleware::class, Storage::PRIORITY_MAXIMAL);
        $storage->add(DbSyncMiddleware::class, Storage::PRIORITY_MAXIMAL);


        $client
            ->stream('messages', ['roomId' => $rooms[0]])
            ->subscribe(function ($data) use ($storage) {
                Thread::create(
                    new class(['storage' => $storage, 'data' => $data]) extends Worker
                    {
                        final public function run()
                        {
                            $this->storage->handle(new Message($this->data));
                        }
                    }
                );
            });

        $client->run();
    }
}
