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


use App\Gitter\Console\CircleProgress;
use App\Gitter\Middleware\DbSyncMiddleware;
use App\Gitter\Middleware\LoggerMiddleware;
use App\Gitter\Middleware\Storage;
use App\User;
use App\Gitter\Client;
use App\Gitter\Application;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use App\Gitter\Models\UserObject;
use App\Gitter\Models\MessageObject;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;


/**
 * Class StartGitterBot
 * @package App\Console\Commands
 */
class StartGitterBot extends Command
{
    const VERSION = '0.1b';

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
     * @var Container
     */
    protected $container;


    /**
     * Execute the console command.
     *
     * @param Repository $config
     * @param Container $container
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \Exception
     */
    public function handle(Repository $config, Container $container)
    {
        $room = $this->argument('room');
        $token = $config->get('gitter.token');

        // Bind client
        $this->container = $container;
        $container->singleton(Client::class, function () use ($token) {
            return new Client($token);
        });

        $this->listenRoom($container->make(Client::class), $room);
    }

    /**
     * @param Client $client
     * @param $room
     * @throws \LogicException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function listenRoom(Client $client, $room)
    {
        $this->prepare($client, $room);

        $storage = new Storage($this->container, $this->output);
        $storage->add(LoggerMiddleware::class, Storage::PRIORITY_MAXIMAL);
        $storage->add(DbSyncMiddleware::class, Storage::PRIORITY_MAXIMAL);


        $client
            ->stream('messages', ['roomId' => $room])
            ->subscribe(function ($data) use ($storage) {
                $message = new MessageObject($data);
                $storage->handle($message);
            });

        $started = Carbon::now();
        $this->output->newLine(2);
        $this->warn(' [Client started at ' . Carbon::now()->toDayDateTimeString() . ']');

        $client->getEventLoop()->addPeriodicTimer(1, function() use ($started) {
            $mBytes  = number_format(memory_get_usage(true) / 1000 / 1000, 2);
            $uptime    = Carbon::now()->diff($started);

            $message = "\r" . sprintf(
                ' <comment>[%02d:%02d:%02d]:</comment> %smb used         ',
                $uptime->h,
                $uptime->i,
                $uptime->s,
                $mBytes
            );

            $this->output->write($message);
        });



        $client->run();
    }

    /**
     * @param Client $client
     * @param $room
     */
    protected function prepare(Client $client, $room)
    {
        $this->showHeader();

        $this->showRoomInfo($client, $room);

        $this->syncUsers($client, $room);
    }

    /**
     * @param Client $client
     * @param $room
     * @throws \Exception
     */
    protected function showRoomInfo(Client $client, $room)
    {
        $this->output->write(' <comment>Current room:</comment> connection...');

        try {
            $room = $client->request('room.info', ['roomId' => $room]);

        } catch (ClientException $e) {

            throw new \LogicException(sprintf('Room %s not found', $room));
        }

        $this->output->write("\r");
        $pattern = ' <comment>Current room:</comment> <info>[%s]</info> %s';
        $message = sprintf($pattern, Str::substr($room['url'], 1), $room['topic'] ?: $room['name']);
        $this->output->writeln($message);
    }

    /**
     * @TODO Create external class for cli processes
     *
     * @param Client $client
     * @param $room
     */
    protected function syncUsers(Client $client, $room)
    {
        $progress = new CircleProgress();

        $this->output->write(' <comment>Sync users:</comment> connection...');

        $users = $client->request('room.users', ['roomId' => $room]);
        $size = count($users);

        foreach ($users as $index => $user) {
            $instance = User::fromGitterObject(new UserObject($user));

            $this->output->write("\r");
            $message = sprintf(
                ' <comment>%s</comment> %s <info>[%s/%s]</info> %-80s',
                'Sync users:',
                $progress->get(),
                $index + 1,
                $size,
                $instance->name
            );
            $this->output->write($message);
            flush();
        }

        $this->output->write("\r");
        $this->output->writeln(sprintf(' <comment>Sync users:</comment> <info>[OK]</info>%80s', ''));
    }

    /**
     * @return void
     */
    protected function showHeader()
    {
        $this->output->newLine();
        $this->comment(sprintf(' %\'-60s', ''));
        $this->comment(sprintf('%23sGitter Bot v%s%22s', '', static::VERSION, ''));
        $this->comment(sprintf(' %\'-60s', ''));
        $this->output->newLine();
    }
}
