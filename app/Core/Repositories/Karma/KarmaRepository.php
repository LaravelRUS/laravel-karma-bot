<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 21:03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\Karma;

use Domains\Karma\Karma;
use Illuminate\Support\Collection;

/**
 * Interface KarmaRepository
 * @package Core\Repositories\Karma
 */
interface KarmaRepository
{
    /**
     * @param string $userId
     * @return Karma[]|Collection
     */
    public function getLatestKarmaForUserId(string $userId) : Collection;
}