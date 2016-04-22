<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 12.04.2016 15:38
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Gitter;


use Core\Io\Commands\Auth;
use Core\Io\IoInterface;
use Core\Repositories\Services\GitterServiceRepository;
use Domains\Karma\Karma;
use Domains\Message\Message;
use Domains\Room\Room;
use Domains\User\Mention;
use Domains\User\User;
use Ds\Map;
use Gitter\Client;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Interfaces\Gitter\Factories\MessageFactory;
use Interfaces\Gitter\Factories\RoomFactory;
use Interfaces\Gitter\Factories\UserFactory;
use Serafim\Evacuator\Evacuator;

/**
 * Class GitterIo
 * @package Interfaces\Gitter
 */
class GitterIo
{
    /**
     * @var array
     */
    private $listenModels = [
        Karma::class,
        Message::class,
        Room::class,
        Mention::class,
        User::class,
    ];

    /**
     * @var User|null
     */
    private $authUser = null;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var IoInterface
     */
    private $io;

    /**
     * @var GitterServiceRepository
     */
    private $services;

    /**
     * @var UserFactory
     */
    private $users;

    /**
     * @var MessageFactory
     */
    private $messages;

    /**
     * @var RoomFactory
     */
    private $rooms;

    /**
     * @var Map
     */
    private $streams;

    /**
     * GitterIo constructor.
     * @param Container $app
     * @param Client $client
     * @param IoInterface $io
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function __construct(Container $app, Client $client, IoInterface $io)
    {
        $this->rooms = new Map;
        $this->client = $client;
        $this->io = $io;

        $this->services = $app->make(GitterServiceRepository::class);
        $this->rooms = $app->make(RoomFactory::class);
        $this->users = $app->make(UserFactory::class);
        $this->messages = $app->make(MessageFactory::class, ['factory' => $this->users]);

        foreach ($this->listenModels as $model) {
            $model::created(function (Model $data) use ($io, $model) {
                $io->entity($model)->fire('created', $data);
            });
        }

        $this->io->onCommand(Auth::class)->then(function ($data) {
            return $this->auth();
        });
    }

    /**
     * @return User
     * @throws \Exception
     * @throws \RuntimeException
     */
    private function auth() : User
    {
        if ($this->authUser === null) {
            $data = $this->client->http->getCurrentUser()->wait();
            $this->authUser = $this->users->fromUser($data[0]);
        }

        return $this->authUser;
    }

    /**
     * @return void
     * @throws \LogicException
     */
    public function run()
    {
        $rooms = $this->getAvailableRooms();

        $this->listenStreams($rooms);

        $this->io->run();
    }

    /**
     * @return Collection
     */
    private function getAvailableRooms() : Collection
    {
        $rooms = new Collection();
        $request = rescue(function () {
            return $this->client->http->getRooms()->wait();
        });

        $responseRooms = $request(Evacuator::INFINITY_RETRIES);

        foreach ($responseRooms as $data) {
            $rooms->push($this->rooms->fromData($data));
        }

        return $rooms
            ->filter(function ($data) {
                // TODO check $data->lastAccessTime
                return true;
            });
    }

    /**
     * @param Collection $rooms
     * @throws \LogicException
     * @throws \Throwable
     */
    private function listenStreams(Collection $rooms)
    {
        /** @var Room $room */
        foreach ($rooms as $room) {
            $service = $this->services->findByInternalId($room->id);

            $this->client->stream->onMessage($service->service_id, function ($data) use ($service) {

                \DB::transaction(function () use ($data, $service) {
                    $this->messages->fromMessage($data, $service->service_id);
                });

            });
        }
    }

    /**
     * @param string $text
     * @return array
     */
    private function botAnswer(string $text)
    {
        $result = [];
        $lines = explode("\n", $text);
        $codeOpen = false;

        foreach ($lines as $line) {
            $isCodeLine = Str::startsWith($line, '```');

            if ($isCodeLine && !$codeOpen) {
                $codeOpen = true;
            }

            $result[] = !$codeOpen && trim($line) && !$isCodeLine
                ? '_' . $line . '_'
                : $line;
        }

        return implode("\n", $result);
    }
}
