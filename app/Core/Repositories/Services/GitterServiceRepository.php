<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 16:25
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\Services;
use Core\Repositories\Repository;
use Domains\Services\Gitter;
use Domains\Services\Service;

/**
 * Class GitterServiceRepository
 * @package Core\Repositories\Services
 */
class GitterServiceRepository extends EloquentServiceRepository
{
    /**
     * GitterServiceRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Gitter::class);
    }

    /**
     * @param string $serviceId
     * @return Service|null
     */
    public function findByServiceId(string $serviceId)
    {
        return $this->queryByServiceId($serviceId)
            ->where('name', Gitter::getName())
            ->first();
    }

    /**
     * @param string $id
     * @return Service|null
     */
    public function findByInternalId(string $id)
    {
        return $this->queryByInternalId($id)
            ->where('name', Gitter::getName())
            ->first();
    }
}