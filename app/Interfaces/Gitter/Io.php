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

use Core\Io\Bus;
use Domains\Room\Room;
use Gitter\Client;

/**
 * Class Io
 * @package Interfaces\Gitter
 */
class Io extends Bus
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Room
     */
    private $room;

    /**
     * Response constructor.
     * @param Client $client
     * @param Room $room
     */
    public function __construct(Client $client, Room $room)
    {
        $this->client = $client;
        $this->room = $room;
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function send($data) : bool
    {
        if ($this->isDisabled()) {
            return true;
        }

        $data = $this->parseAnswer($data);

        if ($data) {
            try {
                $this->client->http->sendMessage($this->room->id, $data)->wait();
            } catch (\Throwable $e) {
                return false;
            }
        }

        return true;
    }
}
