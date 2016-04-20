<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 21:01
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\Room;
use Domains\Room\Room;

/**
 * Interface RoomsRepository
 * @package Core\Repositories\Room
 */
interface RoomsRepository
{
    /**
     * @param $id
     * @return Room|null
     */
    public function find($id);
}