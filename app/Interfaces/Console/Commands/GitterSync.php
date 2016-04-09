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
use Core\Mappers\UserMapper;
use Domains\Message;
use Domains\User;
use Gitter\Client;
use Gitter\Support\ApiIterator;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Connection;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Serafim\Evacuator\Evacuator;

/**
 * Class GitterSync
 * @package Interfaces\Console\Commands
 */
class GitterSync extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'gitter:sync {room}';


    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Fill users karma from all messages of target room.';

    /**
     * Execute the console command.
     *
     * @param Repository $config
     * @param Container $container
     * @param Connection $db
     *
     * @return mixed
     * @throws \Throwable
     */
    public function handle(Repository $config, Container $container, Connection $db)
    {
        $config->set('gitter.output', false);


        // Sync users
        $db->transaction(function () use ($container) {
            $this->comment('Start users synchronize at ' . ($start = Carbon::now()));
            $container->call([$this, 'importUsers']);
            $this->comment('Ends ' . Carbon::now()->diffForHumans($start));
        });


        $this->output->newLine();


        // Sync messages
        $db->transaction(function () use ($container) {
            $this->comment('Start messages synchronize at ' . ($start = Carbon::now()));
            $container->call([$this, 'importMessages']);
            $this->comment('Ends ' . Carbon::now()->diffForHumans($start));
        });


        $this->output->newLine();
    }

    /**
     * @param Client $client
     * @param Connection $db
     * @throws \Throwable
     */
    public function importMessages(Client $client, Connection $db)
    {
        $limit          = 100;
        $lastMessageId  = null;
        $room           = $this->getRoom($client);
        $rootTimeZone   = new \DateTimeZone('UTC');


        $messages = new ApiIterator(function ($page) use ($client, $room, $limit, &$lastMessageId) {
            $query = ['limit' => $limit];

            if ($lastMessageId !== null) {
                $query['beforeId'] = $lastMessageId;
            }

            $result = $this->rescue(function () use ($room, $query, $client) {
                return $client->http->getMessages($room->id, $query)->wait();
            });

            if (count($result) > 0) {
                $lastMessageId = $result[0]->id;
            }

            return $result;
        });


        foreach ($messages as $i => $message) {
            $data = [
                'id'            => Uuid::uuid4()->toString(),
                'gitter_id'     => $message->id,
                'room_id'       => $room->id,
                'text'          => $message->text,
                'text_rendered' => $message->html,
                'user_id'       => $message->fromUser->id,
                'created_at'    => new Carbon($message->sent, $rootTimeZone),
                'updated_at'    => new Carbon($message->sent, $rootTimeZone),
            ];

            if (property_exists($message, 'editedAt') && $message->editedAt) {
                $data['updated_at'] = new Carbon($message->editedAt, $rootTimeZone);
            }

            /*
             ! Bug: Only 1 item inserting by 1 sql query
             ! @see: https://github.com/gitterHQ/gitter/issues/1184
             !
             ! TODO This operations (delete + insert) will be very slow. Optimize later %)
             */
            try {

                $db->transaction(function () use ($message, $data, $db) {
                    $db->table('messages')->where('gitter_id', $data['gitter_id'])->delete();
                    $db->table('messages')->insert($data);

                    $db->table('urls')->where('message_id', $data['id'])->delete();
                    $db->table('urls')->insert(
                        $this->getUrlsFromMessage($data, (array)$message->urls)
                    );

                    $db->table('mentions')->where('message_id', $data['id'])->delete();
                    $db->table('mentions')->insert(
                        $this->getMentionsFromMessage($data, (array)$message->mentions)
                    );

                });

                $this->info(
                    '#' . $i . ' ' .
                    $data['created_at'] . ' ' .
                    mb_substr(str_replace("\n", '', $data['text']), 0, 32) . '... '

                );

            } catch (\Throwable $e) {

                $this->error($e->getMessage() . "\n" . $e->getTraceAsString());

            }
        }
    }

    /**
     * This command will returns an object of the room
     *
     * @param Client $client
     * @return mixed
     * @throws \Throwable
     */
    private function getRoom(Client $client)
    {
        return $this->rescue(function () use ($client) {
            return $client->http->getRoomById($this->argument('room'))->wait();
        });
    }

    /**
     * This method must be wrap ALL request actions
     *
     * @param \Closure $request
     * @return mixed
     * @throws \Throwable
     */
    private function rescue(\Closure $request)
    {
        return (new Evacuator($request))
            ->retry(Evacuator::INFINITY_RETRIES)
            ->catch(function (\Throwable $e) {
                $this->error($e->getMessage() . "\n" . '// retry again');
                sleep(1);
            })
            ->invoke();
    }

    /**
     * This method run an users export
     *
     * @param Client $client
     * @throws \Throwable
     */
    public function importUsers(Client $client)
    {
        $room = $this->getRoom($client);


        $users = new ApiIterator(function ($page) use ($client, $room) {
            return $this->rescue(function () use ($room, $page, $client) {

                return $client->http->getRoomUsers($room->id, ['limit' => 30, 'skip' => 30 * $page])->wait();

            });
        });


        foreach ($users as $i => $user) {
            $user = UserMapper::fromGitterObject($user);
            $this->comment('#' . $i . ' @' . $user->login);
        }
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
     * @param array $message
     * @param array $mentions
     * @return array
     */
    private function getMentionsFromMessage(array $message, array $mentions)
    {
        $result = [];

        foreach ($mentions as $mention) {
            if (property_exists($mention, 'userId') && $mention->userId) {
                $result[] = [
                    'id'         => Uuid::uuid4()->toString(),
                    'message_id' => $message['id'],
                    'user_id'    => $message['user_id'],
                ];
            }
        }

        return $result;
    }

    /**
     * @param array $message
     * @param array $urls
     * @return array
     */
    private function getUrlsFromMessage(array $message, array $urls)
    {
        $result = [];

        foreach ($urls as $url) {
            $result[] = [
                'id'         => Uuid::uuid4()->toString(),
                'message_id' => $message['id'],
                'url'        => $url->url,
            ];
        }

        return $result;
    }
}
