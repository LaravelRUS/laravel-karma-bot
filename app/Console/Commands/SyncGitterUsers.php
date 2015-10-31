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
        $config->set('gitter.output', false);

        $client = Client::make($config->get('gitter.token'), $this->argument('room'));
        $room = $container->make(Room::class);


        $users = $client->request('room.users', ['roomId' => $room->id]);
        $process = new CircleProgress();

        $message = "\r %s <comment>[%s/%s]</comment> %s%80s";

        $count = count($users);
        $current = 1;
        foreach ($users as $user) {
            $user = User::fromGitterObject($user);
            $this->output->write(sprintf($message, $process->get(), $current, $count, $user->login, ''));
            $current++;
        }

        $this->output->write(sprintf($message, ' ', $count, $count, 'OK', ''));
    }
}
