<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 17:35
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Gitter\Factories;

use Domains\Room\Room as Entity;
use Gitter\Client;

/**
 * Class Room
 * @package Interfaces\Gitter\Factories
 */
class Room
{
    /**
     * @param \StdClass $data
     * @return Entity
     */
    public static function create($data) : Entity
    {
        $room = new Entity($data->url, $data->topic ?: $data->name);
        $room->gitterId = $data->id;

        return $room;
    }

    /**
     * @param Client $client
     * @param string $roomId
     * @return Entity
     * @throws \Exception
     */
    public static function createFromId(Client $client, string $roomId)
    {
        return static::create($client->http->getRoomById($roomId)->wait());
    }

    /**
     * @param Client $client
     * @param string $roomUri
     * @return Entity
     * @throws \Exception
     */
    public static function createFromUri(Client $client, string $roomUri)
    {
        return static::create($client->http->getRoomByUri($roomUri)->wait());
    }
}
