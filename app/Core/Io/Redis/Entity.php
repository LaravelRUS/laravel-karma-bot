<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 14:52
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io\Redis;

use Core\Io\EntityInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Redis\Database;
use React\EventLoop\LoopInterface;

/**
 * Class Entity
 * @package Core\Io\Redis
 */
class Entity implements EntityInterface
{
    /**
     * @var Database
     */
    private $observer;

    /**
     * @var string
     */
    private $name;

    /**
     * Entity constructor.
     * @param RedisObserver $observer
     * @param string $name
     */
    public function __construct(RedisObserver $observer, string $name)
    {
        $this->observer = $observer;
        $this->name = $name;
    }

    /**
     * @param string $event
     * @param \Closure $callback
     * @return EntityInterface
     */
    public function listen(string $event, \Closure $callback) : EntityInterface
    {
        $this->observer->listen($this->channel($event), $callback);

        return $this;
    }

    /**
     * @param string $event
     * @param Model $entity
     * @return EntityInterface
     */
    public function fire(string $event, Model $entity) : EntityInterface
    {
        $this->observer->fire($this->channel($event), $entity);

        return $this;
    }

    /**
     * @param string $event
     * @return string
     */
    private function channel(string $event) : string
    {
        return 'stream:' . $this->name . ':' . $event;
    }
}