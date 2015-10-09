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


use App\User;
use App\Room;
use App\Message;
use App\Gitter\Client;
use App\Gitter\Karma\Validator;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;

/**
 * Class SyncGitterMessages
 * @package App\Console\Commands
 */
class SyncGitterMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitter:messages {room}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill users karma from all messages of target room.';


    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Validator
     */
    protected $karma;


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
        $this->warn('Be sure what users was be synced first!');

        $room           = Room::getId($this->argument('room'));

        $client         = new Client($config->get('gitter.token'));
        $container->bind(Client::class, $client);

        $room   = new Room($client, $room);
        $container->bind(Room::class, $room);

        $this->karma    = new Validator();


        $request = $this->cursor($client, $room);
        $count = 1;   // Start number
        $page = 0;   // Current page
        $chunk = 100; // Per page

        while (true) {
            $messages = $request($chunk, $chunk * $page++);

            if (!count($messages)) {
                $this->output->write(sprintf("\r Well done. <comment>%s</comment> Messages was be parsed.", $count));

                return;
            }

            foreach ($messages as $message) {
                $this->onMessage(Message::fromGitterObject($message), $count);
                $count++;
            }
        }
    }

    /**
     * @param Client $client
     * @param $room
     * @return \Closure
     * @throws \InvalidArgumentException
     */
    public function cursor(Client $client, $room)
    {
        return function ($limit = 100, $skip = 0) use ($client, $room) {
            return $client->request('message.list', [
                'roomId' => $room,
                'limit'  => $limit,
                'skip'   => $skip,
            ]);
        };
    }

    /**
     * @param Message $message
     * @param $count
     */
    protected function onMessage(Message $message, $count)
    {
        $this->karma->validate($message);

        $this->output->write(sprintf("\r <comment>[%s]</comment> %s%80s", $count, $message->text, ''));
    }
}
