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

/**
 * Class Entity
 * @package Core\Io\Redis
 */
class Entity implements EntityInterface
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @var string
     */
    private $name;

    /**
     * Entity constructor.
     * @param Database $database
     * @param string $name
     */
    public function __construct(Database $database, string $name)
    {
        $this->database = $database;
        $this->name = $name;
    }

    /**
     * @param string $event
     * @param \Closure $callback
     * @return EntityInterface
     */
    public function listen(string $event, \Closure $callback) : EntityInterface
    {
        $this->database->subscribe($this->channel($event), function($data) use ($callback) {
            $callback(unserialize($data));
        });
    }

    /**
     * @param string $event
     * @param Model $entity
     * @return EntityInterface
     */
    public function fire(string $event, Model $entity) : EntityInterface
    {
        $this->publish($this->channel($event), serialize($entity));
    }

    /**
     * @param string $event
     * @return string
     */
    private function channel(string $event)
    {
        return $this->name . ':' . $event;
    }

    /**
     * @param string $channel
     * @param $data
     */
    private function publish(string $channel, $data)
    {
        $this->database->command('publish', [$channel, $data]);
    }
}