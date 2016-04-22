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
use Core\Io\IoInterface;
use Domains\User\User;
use Illuminate\Redis\Database;
use React\EventLoop\LoopInterface;

/**
 * Class Io
 * @package Core\Io\Redis
 */
class Io implements IoInterface
{
    /**
     * @var RedisObserver
     */
    private $observer;

    /**
     * @var array|EntityInterface[]|Entity[]
     */
    private $entities = [];

    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * Io constructor.
     * @param Database $database
     * @param LoopInterface $loop
     */
    public function __construct(Database $database, LoopInterface $loop)
    {
        $this->loop = $loop;
        $this->observer = new RedisObserver($database, $loop);
    }

    /**
     * @param string $name
     * @return EntityInterface
     */
    public function entity(string $name) : EntityInterface
    {
        if (!array_key_exists($name, $this->entities)) {
            $this->entities[$name] = new Entity($this->observer, $name);
        }

        return $this->entities[$name];
    }

    /**
     * @param \Closure $callback
     * @return $this|IoInterface
     */
    public function onAuth(\Closure $callback) : IoInterface
    {
        $this->observer->listen('auth', $callback);

        return $this;
    }

    /**
     * @param User $user
     * @return IoInterface
     */
    public function auth(User $user) : IoInterface
    {
        $this->observer->fire('auth', $user);

        return $this;
    }

    /**
     * @retun void
     */
    public function run()
    {
        $this->loop->run();
    }
}