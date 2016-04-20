<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 21:05
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\Karma;

use Core\Repositories\Repository;
use Domains\Karma\Karma;
use Illuminate\Support\Collection;

/**
 * Class EloquentKarmaRepository
 * @package Core\Repositories\Karma
 */
class EloquentKarmaRepository extends Repository implements
    KarmaRepository
{
    /**
     * EloquentKarmaRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Karma::class);
    }

    /**
     * @param string $userId
     * @return Karma[]|Collection
     */
    public function getLatestKarmaForUserId(string $userId) : Collection
    {
        throw new \LogicException(__CLASS__ . '::' . __METHOD__ . ' not implemented yet');
    }
}