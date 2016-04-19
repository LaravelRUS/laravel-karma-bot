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
use Core\Io\Bus;
use Doctrine\ORM\EntityManagerInterface;
use Domains\Achieve\AchieveInterface;
use Domains\Bot\Middlewares;
use Domains\Bot\ProcessId;
use Domains\Message\Message;
use Domains\Room\Room;
use Domains\User\Bot;
use Domains\User\User;
use Gitter\Client;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Application;
use Interfaces\Gitter\Factories\Room as RoomFactory;
use Interfaces\Gitter\Io;


/**
 * Class GitterBot
 */
class GitterBot extends Command
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
    protected $description = 'Start gitter bot process for target room.';

    /**
     * @var string
     */
    protected $pid;

    /**
     * Execute the console command.
     *
     * @param Container|Application $container
     * @param Client $client
     * @param EntityManagerInterface $em
     * @return mixed
     * @throws \Throwable
     * @throws \Exception
     */
    public function handle(Container $container, Client $client, EntityManagerInterface $em)
    {
        $this->registerSqlLogger();

        try {
            // Current room
            $room = $this->getRoom($client);
            $this->comment('Join to room [' . $room->url . ']');


            $container->singleton(Bus::class, function (Container $app) use ($room) {
                return $app->make(Io::class, ['room' => $room]);
            });

            $io = $container->make(Bus::class);

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

            /** @var Dispatcher $events */
            $events = $container->make('events');
            $events->listen(AchieveInterface::EVENT_ADD, function (AchieveInterface $achieve, User $user) use ($io, $em) {
                $user->addAchieve($achieve);

                $em->persist($user);

                $message = trans('achieve.receiving', [
                    'title'       => $achieve->getTitle(),
                    'user'        => $user->credinals->login,
                    'description' => $achieve->getDescription(),
                    'image'       => $achieve->getImage(),
                ]);

                $io->send($message);
            });


            $io->onMessage(function (Message $message) use ($middlewares, $io, $em, $user) {
                $em->persist($message);
                $em->flush();

                $result = $middlewares->handle($message);

                $io->send($result);

                $em->merge($message);
                $em->flush();
            });


            $this->output->newLine();
            $this->info(sprintf('KarmaBot %s started at %s', '0.2b', Carbon::now()));


            $io->listen();

        } catch (\Throwable $e) {
            throw $e;

        }
    }

    /**
     * @return void
     */
    private function registerSqlLogger()
    {
        \Registry::getConnection()
            ->getConfiguration()
            ->setSQLLogger(new SqlMemoryLogger());
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
