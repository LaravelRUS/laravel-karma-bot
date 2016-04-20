<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 21:02
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\Room;

use Core\Repositories\Repository;
use Domains\Room\Room;

/**
 * Class EloquentRoomsRepository
 * @package Core\Repositories\Room
 */
class EloquentRoomsRepository extends Repository implements
    RoomsRepository
{
    /**
     * EloquentRoomsRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Room::class);
    }
}