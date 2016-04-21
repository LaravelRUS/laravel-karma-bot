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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class EloquentServiceRepository
 * @package Core\Repositories\Services
 */
abstract class EloquentServiceRepository extends Repository implements
    ServiceRepository
{
    /**
     * @param string $serviceId
     * @return Service|Builder
     */
    protected function queryByServiceId(string $serviceId)
    {
        return $this->query()->where('service_id', $serviceId);
    }

    /**
     * @param string $id
     * @return Service|Builder
     */
    protected function queryByInternalId(string $id)
    {
        return $this->query()->where('id', $id);
    }

    /**
     * @param string $serviceId
     * @return Service
     */
    public function findOrCreateByServiceId(string $serviceId) : Service
    {
        $result = $this->get($serviceId);

        if ($result === null) {
            $result = $this->findByServiceId($serviceId);

            if ($result === null) {
                /** @var Model $entity */
                $entity = get_class($this->getEntity());
                $result = $entity::create([
                    'id'         => Uuid::uuid3(md5($serviceId), $serviceId)->toString(),
                    'service_id' => $serviceId,
                ]);
            }

            $this->store($serviceId, $result);
        }

        return $result;
    }
}