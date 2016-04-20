<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 17:05
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Gitter\Factories;

use Core\Repositories\Services\GitterServiceRepository;
use Domains\Services\Gitter;
use Domains\Services\Service;
use Ds\Map;
use Ramsey\Uuid\Uuid;

/**
 * Class ServiceFactory
 * @package Interfaces\Gitter\Factories
 */
class ServiceFactory
{
    /**
     * @var GitterServiceRepository
     */
    private $services;

    /**
     * @var Map
     */
    private $identityMap;

    /**
     * Factory constructor.
     * @param GitterServiceRepository $services
     */
    public function __construct(GitterServiceRepository $services)
    {
        $this->identityMap = new Map();
        $this->services    = $services;
    }

    /**
     * @param string $serviceId
     * @return Service
     */
    public function fromServiceId(string $serviceId) : Service
    {
        if ($this->identityMap->hasKey($serviceId)) {
            return $serviceId;
        }

        $service = $this->services->findByServiceId($serviceId);

        if ($service === null) {
            $service = Gitter::create([
                'id'         => Uuid::uuid3(md5($serviceId), $serviceId)->toString(),
                'service_id' => $serviceId,
            ]);

            $this->identityMap->put($serviceId, $service);
        }

        return $service;
    }
}