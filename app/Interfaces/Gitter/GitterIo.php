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


use Core\Io\IoInterface;
use Core\Repositories\Room\RoomsRepository;
use Core\Repositories\Services\GitterServiceRepository;
use Domains\Room\Room;
use Ds\Map;
use Gitter\Client;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Interfaces\Gitter\Factories\MessageFactory;
use Interfaces\Gitter\Factories\RoomFactory;
use Interfaces\Gitter\Factories\ServiceFactory;
use Interfaces\Gitter\Factories\UserFactory;
use React\EventLoop\LoopInterface;
use Serafim\Evacuator\Evacuator;

/**
 * Class GitterIo
 * @package Interfaces\Gitter
 */
class GitterIo
{
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
        $this->rooms    = new Map;
        $this->client   = $client;
        $this->io       = $io;

        $this->services = $app->make(GitterServiceRepository::class);
        $this->rooms    = $app->make(RoomFactory::class);
        $this->users    = $app->make(UserFactory::class);
        $this->messages = $app->make(MessageFactory::class, [
            'factory' => $this->users,
        ]);

        $this->auth();
    }

    /**
     * @return $this|GitterIo
     * @throws \Exception
     * @throws \RuntimeException
     */
    private function auth() : GitterIo
    {
        $data = $this->client->http->getCurrentUser()->wait();
        $user = $this->users->fromUser($data[0]);

        $this->io->auth($user);

        return $this;
    }

    /**
     * @return $this|GitterIo
     */
    public function listen() : GitterIo
    {
        /** @var LoopInterface $loop */
        $loop = $this->client->stream->getEventLoop();

        $rooms = $this->getAvailableRooms();

        $this->listenStreams($rooms);

        $this->client->stream->listen();

        return $this;
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
     * @param array $rooms
     */
    private function listenStreams(Collection $rooms)
    {
        /** @var Room $room */
        foreach ($rooms as $room) {
            $service = $this->services->findByInternalId($room->id);

            $this->client->stream->onMessage($service->service_id, function ($data) use ($service) {
                $message = $this->messages->fromMessage($data, $service->service_id);
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
