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

use Core\Io\Commands\Command;
use Core\Io\EntityInterface;
use Core\Io\IoInterface;
use Illuminate\Redis\Database;
use React\EventLoop\LoopInterface;
use React\Promise\Promise;

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
     * @param Command $command
     * @return Promise
     */
    public function command(Command $command) : Promise
    {
        return new Promise(function ($resolver) use ($command) {
            $name = get_class($command);

            $this->observer->listen($name, function ($data) use ($name, $resolver) {
                $resolver($data);
            });

            $this->observer->fire('wants:' . $name, $command);
        });
    }

    /**
     * @param string $command Command class name
     * @return Promise
     */
    public function onCommand(string $command) : Promise
    {
        return new Promise(function ($resolver) use ($command) {
            $this->observer->listen('wants:' . $command, function ($data) use ($command, $resolver) {
                $result = $resolver($data);

                $this->observer->fire($command, $result);
            });
        });
    }

    /**
     * @retun void
     */
    public function run()
    {
        $this->loop->run();
    }
}