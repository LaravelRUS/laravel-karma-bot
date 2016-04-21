<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 21.04.2016 15:13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories;

use Ds\Map;

/**
 * Class IdentityMap
 * @package Core\Repositories
 */
class IdentityMap
{
    /**
     * @var Map
     */
    private $identityMap;

    /**
     * IdentityMap constructor.
     */
    public function __construct()
    {
        $this->identityMap = new Map;
    }

    /**
     * @param $key
     * @param $value
     * @return void
     * @throws \OutOfBoundsException
     */
    public function store($key, $value)
    {
        $this->identityMap->put($key, $value);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        if ($this->identityMap->hasKey($key)) {
            return $this->identityMap->get($key);
        }
        return null;
    }
}