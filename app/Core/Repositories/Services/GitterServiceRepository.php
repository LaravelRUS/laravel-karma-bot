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
class GitterServiceRepository extends Repository implements
    ServiceRepository
{
    /**
     * GitterServiceRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Gitter::class);

        $this->setEntity(Gitter::class);
    }

    /**
     * @param string $serviceId
     * @return Service|null
     */
    public function findByServiceId(string $serviceId)
    {
        return $this->query()
            ->where('name', Gitter::getName())
            ->where('service_id', $serviceId)
            ->first();
    }

    /**
     * @param string $id
     * @return Service|null
     */
    public function findByInternalId(string $id)
    {
        return $this->query()
            ->where('name', Gitter::getName())
            ->where('id', $id)
            ->first();
    }
}