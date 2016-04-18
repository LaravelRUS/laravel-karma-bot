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
use Core\Repositories\KarmaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Domains\Bot\Middlewares\Karma\Validation\Validator;
use Domains\Room\Room;
use Gitter\Client;
use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Interfaces\Gitter\Factories\Room as RoomFactory;
use Interfaces\Gitter\Factories\Message as MessageFactory;
use Interfaces\Gitter\Io;

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
    protected $description = 'Start gitter bot syncronization.';

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


        $validator = new Validator(new KarmaRepository($em));

        $iterator = $client->http->getMessagesIterator($room->id);
        foreach ($iterator as $data) {
            $message = MessageFactory::create($data, $room);
            try {
                $em->persist($message);
                $em->flush();
                $this->info('Message: ' . mb_substr($message->text->inline, 0, 64));

            } catch (\Throwable $e) {
                $this->error($e->getMessage());
            }
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