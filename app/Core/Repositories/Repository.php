<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 15.04.2016 16:45
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories;
use Ds\Map;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Repository
 * @package Core\Repositories
 */
abstract class Repository
{
    /**
     * @var Map
     */
    protected static $identity = null;

    /**
     * @var Model|Builder
     */
    private $entity;

    /**
     * Repository constructor.
     * @param string $entity
     */
    public function __construct(string $entity)
    {
        if (static::$identity === null) {
            static::$identity = new Map;
        }

        $this->setEntity($entity);
    }

    /**
     * @return IdentityMap
     */
    private function getIdentityMap() : IdentityMap
    {
        $key = get_class($this->entity);

        if (!static::$identity->hasKey($key)) {
            static::$identity->put($key, new IdentityMap());
        }

        return static::$identity->get($key);
    }

    /**
     * @param $key
     * @param $value
     * @throws \OutOfBoundsException
     */
    protected function store($key, $value)
    {
        $this->getIdentityMap()->store($key, $value);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    protected function get($key)
    {
        return $this->getIdentityMap()->get($key);
    }

    /**
     * @param string $entity
     * @return Repository
     */
    protected function setEntity(string $entity) : Repository
    {
        $this->entity = new $entity;
        return $this;
    }

    /**
     * @return Model
     */
    protected function getEntity() : Model
    {
        return $this->entity;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function query()
    {
        return $this->entity->newQuery();
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id)
    {
        return $this->entity->find($id);
    }
}
