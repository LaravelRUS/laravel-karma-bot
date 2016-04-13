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

namespace Interfaces\Console\Commands;


use Carbon\Carbon;
use Domains\Bot\Middlewares;
use Domains\Bot\Pid;
use Domains\Bot\ProcessId;
use Domains\Message\Message;
use Domains\Room\Room;
use Domains\User\Bot;
use Domains\User\User;
use Gitter\Client;
use Interfaces\Gitter\Io;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Interfaces\Gitter\Factories\User as UserFactory;
use Interfaces\Gitter\Factories\Room as RoomFactory;
use Interfaces\Gitter\Factories\Message as MessageFactory;


/**
 * Class StartGitterBot
 */
class StartGitterBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitter:listen {room}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start gitter bot thread for target room.';

    /**
     * @var string
     */
    protected $pid;

    /**
     * Execute the console command.
     *
     * @param Repository $config
     * @param Container $container
     * @param Client $client
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \Exception
     */
    public function handle(Repository $config, Container $container, Client $client)
    {
        // Create an a pid file
        $this->pid   = new ProcessId();
        $this->pid->create();

        // Current room
        $room        = $this->getRoom($config, $client);

        // Gitter Io
        $io          = new Io($client, $room);

        // Current authenticated user
        $user        = $io->auth();

        // Middlewares
        $middlewares = Middlewares::new($container, $room, $io)->ignore($user);


        $container->singleton(Bot::class, function() use ($user) { return $user; });

        $io->onMessage(function(Message $message) use ($middlewares, $io) {
            $io->send($middlewares->handle($message));
        });

        $this->info(sprintf('KarmaBot %s started at %s', '0.2b', Carbon::now()));

        $io->listen();
        
        $this->pid->delete();
    }

    /**
     * @param Repository $config
     * @param Client $client
     * @return Room
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    private function getRoom(Repository $config, Client $client) : Room
    {
        $rooms = $config->get('gitter.rooms');
        if (!array_key_exists($this->argument('room'), $rooms)) {
            throw new \InvalidArgumentException('Can not resolve room ' . $this->argument('room'));
        }

        return RoomFactory::createFromId($client, $rooms[$this->argument('room')]);
    }
}
