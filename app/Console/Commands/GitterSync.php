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
use Symfony\Component\Finder\Finder;

/**
 * Class GitterSync
 * @package App\Console\Commands
 */
class GitterSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitter:sync {room}';


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
        $this->syncUsers($config, $container);

        $config->set('gitter.output', false);

        $client = Client::make($config->get('gitter.token'), $this->argument('room'));
        $room = $container->make(Room::class);

        $this->karma = new Validator();


        $request = $this->cursor($client, $room);
        $count = 1;   // Start number
        $page = 0;   // Current page
        $chunk = 1000; // Per page


        while (true) {
            $messageChunk = $request($chunk, $chunk * $page++);

            if (!count($messageChunk)) {
                $this->output->write(sprintf("\r Well done. <comment>%s</comment> Messages was be loaded.", $count));
                break;
            }


            foreach ($messageChunk as $m) {
                echo "\rLoad message: $count ";
                $count++;
            }

            $name = 'sync/' . $page . '.json';
            echo '...dump to ' . $name;
            file_put_contents(
                storage_path($name),
                json_encode($messageChunk)
            );
        }


        echo "\n";

        $this->output->write('Flush database karma increments');
        Karma::query()
            ->where('room_id', $room->id)
            ->delete();


        $this->output->write('Start message parsing.');
        $finder = (new Finder())
            ->files()
            ->in(storage_path('sync'))
            ->name('*.json')
            ->sort(function($a, $b) {
                $parse = function(\SplFileInfo $file) {
                    return str_replace('.json', '', $file->getFilename());
                };

                return $parse($b) <=> $parse($a);
            });


        $count = 1;
        foreach ($finder as $file) {
            $messages = json_decode($file->getContents(), true);
            foreach ($messages as $message) {
                $message = Message::fromGitterObject($message);

                echo "\r" . $count++ . ' messages parsing: ' . $message->created_at;
                usleep(100);
                $this->onMessage($message);
            }

            unlink($file->getRealPath());
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
     * @throws InvalidArgumentException
     */
    protected function onMessage(Message $message)
    {
        $collection = $this->karma->validate($message);

        foreach ($collection as $state) {
            $user = $state->getUser();

            if ($state->isIncrement()) {
                $message->user->addKarmaTo($user, $message);
            }

            if ($state->isIncrement() || $state->isTimeout() || $state->isSelf()) {
                echo "\r" . '[' . $message->created_at . '] ' .
                    $state->getTranslation($user->karma_text) . "\n";
            }
        }
    }

    /**
     * @param Repository $config
     * @param Container $container
     */
    public function syncUsers(Repository $config, Container $container)
    {
        $this->output->write('Start user sync...');
        $config->set('gitter.output', false);

        $client = Client::make($config->get('gitter.token'), $this->argument('room'));
        $room = $container->make(Room::class);


        $users = $client->request('room.users', ['roomId' => $room->id]);
        $message = "\r<comment>[%s/%s]</comment> %s%80s";

        $count = count($users);
        $current = 1;
        foreach ($users as $user) {
            $user = User::fromGitterObject($user);
            $this->output->write(sprintf($message, $current, $count, $user->login, ''));
            $current++;
        }

        $this->output->write(sprintf($message, $count, $count, 'OK', ''));
    }
}
