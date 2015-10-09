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
use App\Room;
use App\Gitter\Client;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;


/**
 * Class SyncGitterUsers
 * @package App\Console\Commands
 */
class SyncGitterUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitter:users {room}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill users table from users of target room.';


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
        $room   = Room::getId($this->argument('room'));
        $token  = $config->get('gitter.token');


        $client = new Client($token);
        $container->bind(Client::class, $client);

        $room   = new Room($client, $room);
        $container->bind(Room::class, $room);


        $users      = $client->request('room.users', ['roomId' => $room]);
        $process    = new CircleProgress();

        $message    = "\r%s<comment>[%s/%s]</comment> %s%80s";

        $count      = count($users);
        $current    = 1;
        foreach ($users as $user) {
            $user = User::fromGitterObject($user);
            $this->output->write(sprintf($message, $process->get(), $current, $count, $user->login, ''));
            $current++;
        }

        $this->output->write(sprintf($message, ' ', $count, $count, 'OK', ''));
    }
}
