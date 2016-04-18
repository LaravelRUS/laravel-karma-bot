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
use Core\Doctrine\SqlMemoryLogger;
use Core\Repositories\MessageRepository;

use Doctrine\ORM\EntityManagerInterface;
use Domains\Bot\Middlewares;
use Domains\Bot\ProcessId;
use Domains\Message\Message;
use Domains\Room\Room;
use Domains\User\Bot;
use Gitter\Client;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application;
use Interfaces\Gitter\Factories\Room as RoomFactory;
use Interfaces\Gitter\Io;


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
     * @param Container|Application $container
     * @param Client $client
     * @param EntityManagerInterface $manager
     * @return mixed
     * @throws \Throwable
     */
    public function handle(Container $container, Client $client, EntityManagerInterface $manager)
    {
        \Registry::getConnection()
            ->getConfiguration()
            ->setSQLLogger(new SqlMemoryLogger());


        $this->call('doctrine:generate:proxies');

        // Create an a pid file
        $this->pid = new ProcessId();
        $this->pid->create();

        try {
            // Current room
            $room = $this->getRoom($client);
            $this->comment('Join to room [' . $room->url . ']');

            // Gitter Io
            $io = new Io($client, $room);

            // Current authenticated user
            $user = $io->auth();

            $this->comment('Login as [' . $user->credinals->login . ']');

            // Middlewares
            $middlewares = Middlewares::new($container, $room, $io)->ignore($user);

            $this->info('Loading middlewares:');
            foreach ($middlewares->getRegisteredMiddlewares() as $middleware) {
                $this->comment(' > ' . get_class($middleware));
            }

            $container->singleton(Bot::class, function () use ($user) {
                return $user;
            });

            $this->info(str_repeat('=', 80));
            

            $io->onMessage(function (Message $message) use ($middlewares, $io, $manager, $user) {
                $manager->persist($message);

                $manager->flush();

                $result = $middlewares->handle($message);

                $io->send($result);
            });


            $this->output->newLine();
            $this->info(sprintf('KarmaBot %s started at %s', '0.2b', Carbon::now()));


            $io->listen();

        } catch (\Throwable $e) {
            throw $e;

        } finally {

            $this->pid->delete();
        }
    }

    /**
     * @param Client $client
     * @return Room
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    private function getRoom(Client $client) : Room
    {
        return RoomFactory::createFromUri($client, $this->argument('room'));
    }
}
