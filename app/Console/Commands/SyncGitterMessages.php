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

use App\Karma;
use App\User;
use App\Room;
use App\Message;
use App\Gitter\Client;
use InvalidArgumentException;
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
        $config->set('gitter.output', false);
        $this->warn('Be sure what users was be synced first!');

        $client      = Client::make($config->get('gitter.token'), $this->argument('room'));
        $room        = $container->make(Room::class);

        $this->karma = new Validator();


        $request     = $this->cursor($client, $room);
        $count       = 1;   // Start number
        $page        = 0;   // Current page
        $chunk       = 100; // Per page


        Karma::query()
            ->where('room_id', $room->id)
            ->delete();

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
     * @param Room $room
     * @return \Closure
     * @throws \InvalidArgumentException
     */
    public function cursor(Client $client, Room $room)
    {
        return function ($limit = 100, $skip = 0) use ($client, $room) {
            return $client->request('message.list', [
                'roomId' => $room->id,
                'limit'  => $limit,
                'skip'   => $skip,
            ]);
        };
    }

    /**
     * @param Message $message
     * @param $count
     * @throws InvalidArgumentException
     */
    protected function onMessage(Message $message, $count)
    {
        $status = $this->karma->validate($message);

        if ($status->isIncrement()) {
            foreach ($message->mentions as $user) {
                $message->user->addKarmaTo($user, $message);
            }

        } else if ($status->isDecrement()) {
            foreach ($message->mentions as $user) {
                $message->user->addKarmaTo($user, $message);
            }
        }

        if ($status->isIncrement() || $status->isDecrement()) {
            $msg = mb_substr(str_replace(["\r", "\n"], '', trim($message->text)), 0, 100);

            $this->output->write(sprintf("\r" . '<comment>[%s]</comment> %s%80s', $count, $msg, ''));
        }
    }
}
