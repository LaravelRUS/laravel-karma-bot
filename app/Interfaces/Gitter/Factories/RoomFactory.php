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

use Core\Entity\Builder;
use Core\Repositories\Room\RoomsRepository;
use Core\Repositories\Services\GitterServiceRepository;
use Domains\Room\Room;


/**
 * Class RoomFactory
 * @package Interfaces\Gitter\Factories
 */
class RoomFactory extends ServiceFactory
{
    /**
     * @var RoomsRepository
     */
    private $rooms;

    /**
     * RoomFactory constructor.
     * @param GitterServiceRepository $services
     * @param RoomsRepository $rooms
     */
    public function __construct(GitterServiceRepository $services, RoomsRepository $rooms)
    {
        parent::__construct($services);
        $this->rooms = $rooms;
    }

    /**
     * @param \StdClass $data
     * @param bool $sync
     * @return Room
     */
    public function fromData($data, $sync = false) : Room
    {
        $service = $this->fromServiceId($data->id);

        /** @var Room $room */
        $room = $this->rooms->find($service->id);

        if ($room === null) {
            $room = new Room(['id' => $service->id]);
            $sync = true;
        }

        if ($sync) {
            $url = $data->uri ?? substr($data->url, 1);
            $room->setRawAttributes(array_merge($room->getAttributes(), [
                'title' => $data->topic ?: $url,
                'url'   => $url,
            ]));

            $room->save();
        }

        return $room;
    }
}
