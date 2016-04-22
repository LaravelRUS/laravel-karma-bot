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
use Ds\Set;
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
     * @var array|Set|Model[]
     */
    private static $uow = null;

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

        if (static::$uow === null) {
            static::$uow = new Set;
        }

        $this->setEntity($entity);
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id)
    {
        return $this->entity->find($id);
    }

    /**
     * @param Model $model
     */
    public function save(Model $model)
    {
        $this->store($model);
        $this->flush();
    }

    /**
     * @param Model $model
     * @return Repository
     */
    public function store(Model $model) : Repository
    {
        static::$uow->add($model);

        return $this;
    }

    /**
     * @throws \Throwable
     * @return bool
     */
    public function flush() : bool
    {
        return $this->transaction(function () {
            $uow = static::$uow;

            foreach ($uow as $model) {
                $model->save();
            }

            static::$uow->clear();

            return true;
        });
    }

    /**
     * @param \Closure $callback
     * @return mixed
     */
    abstract protected function transaction(\Closure $callback);

    /**
     * @param $key
     * @param $value
     * @throws \OutOfBoundsException
     */
    protected function storeIdentity($key, $value)
    {
        $this->getIdentityMap()->store($key, $value);
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
     * @return mixed|null
     */
    protected function getIdentity($key)
    {
        return $this->getIdentityMap()->get($key);
    }

    /**
     * @return Model
     */
    protected function getEntity() : Model
    {
        return $this->entity;
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
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    protected function query()
    {
        return $this->entity->newQuery();
    }
}
