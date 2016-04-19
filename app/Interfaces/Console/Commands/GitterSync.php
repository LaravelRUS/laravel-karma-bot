<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 18.04.2016 20:46
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Console\Commands;

use Core\Doctrine\SqlMemoryLogger;
use Core\Io\Bus;
use Doctrine\ORM\EntityManagerInterface;
use Domains\Room\Room;
use Gitter\Client;
use Gitter\Support\RequestIterator;
use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Interfaces\Gitter\Factories\Message as MessageFactory;
use Interfaces\Gitter\Factories\Room as RoomFactory;
use Interfaces\Gitter\Factories\User as UserFactory;
use Interfaces\Gitter\Io;
use Serafim\Evacuator\Evacuator;

/**
 * Class GitterSync
 * @package Interfaces\Console\Commands
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
    protected $description = 'Start gitter bot messages (and users) syncronization.';

    /**
     * @param Container $container
     * @param Client $client
     * @param EntityManagerInterface $em
     */
    public function handle(Container $container, Client $client, EntityManagerInterface $em)
    {
        \Registry::getConnection()
            ->getConfiguration()
            ->setSQLLogger(new SqlMemoryLogger());

        // Current room
        $room = $this->getRoom($client);
        $this->comment('Join to room [' . $room->url . ']');

        $container->singleton(Bus::class, function (Container $app) use ($room) {
            return $app->make(Io::class, ['room' => $room]);
        });

        $iterator = $client->http->getRoomUsersIterator($room->id);
        foreach ($iterator as $data) {
            $user = UserFactory::create($data);
            $this->info('User @' . $user->credinals->login);
            $em->merge($user);
            $em->flush();
        }


        $iterator = $this->getMessagesIterator($client, $room->id);


        $i = 0;
        $chunk = [];
        foreach ($iterator as $data) {
            $i++;
            $message = MessageFactory::create($data, $room);

            try {
                $chunk[] = $message;
                $em->persist($message);
                $em->flush();

                if ($i > 100) {
                    foreach ($chunk as $m) {
                        $this->comment('Message: ' . mb_substr($m->text->inline, 0, 64));
                    }
                    $chunk = [];
                    $i = 0;
                }

            } catch (\Throwable $e) {

            }
        }
    }

    /**
     * @param Client $client
     * @param string $roomId
     * @param int $chain
     * @return RequestIterator
     */
    private function getMessagesIterator(Client $client, string $roomId, int $chain = 100)
    {
        $lastMessageId  = null;

        return new RequestIterator(function($page) use ($client, $roomId, $chain, &$lastMessageId) {
            $query = ['limit' => $chain];

            if ($lastMessageId !== null) {
                $query['beforeId'] = $lastMessageId;
            }

            $rescuer = rescue(function() use ($client, $roomId, $query) {
                return $client->http->getMessages($roomId, $query)->wait();
            });

            $result = $rescuer(Evacuator::INFINITY_RETRIES);

            if (count($result) > 0) {
                $lastMessageId = $result[0]->id;
            }

            return $result;
        });
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