<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 16:17
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\Services;

use Domains\Services\Service;

/**
 * Interface ServiceRepository
 * @package Core\Repositories\Services
 */
interface ServiceRepository
{
    /**
     * @param string $serviceId
     * @return Service|null
     */
    public function findByServiceId(string $serviceId);

    /**
     * @param string $id
     * @return Service|null
     */
    public function findByInternalId(string $id);
}