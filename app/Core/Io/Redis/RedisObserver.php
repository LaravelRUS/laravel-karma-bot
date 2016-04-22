<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 22.04.2016 15:24
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io\Redis;

use Ds\Set;
use Illuminate\Redis\Database;
use React\EventLoop\LoopInterface;

/**
 * Class RedisObserver
 * @package Core\Io\Redis
 */
class RedisObserver
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var array|Set[]
     */
    private $listeners = [];

    /**
     * @var float
     */
    private $fetchSpeed = 0.5;

    /**
     * RedisDatabase constructor.
     * @param Database $database
     * @param LoopInterface $loop
     */
    public function __construct(Database $database, LoopInterface $loop)
    {
        $this->database = $database;
        $this->loop = $loop;

        $this->loop->addPeriodicTimer($this->fetchSpeed, function() {

            foreach ($this->listeners as $name => $listeners) {
                $eventKey = $this->database->command('lpop', [$name]);

                if ($eventKey && $data = $this->database->command('get', [$eventKey])) {
                    $this->database->command('del', [$eventKey]);

                    foreach ($listeners as $listener) {
                        $listener($this->unserialize($data));
                    }
                }
            }
        });
    }

    /**
     * @param string $event
     * @param $data
     */
    public function fire(string $event, $data)
    {
        $key = $this->createEvent($event);

        $this->database->command('set', [$key, $this->serialize($data)]);
    }

    /**
     * @param string $event
     * @param \Closure $callback
     */
    public function listen(string $event, \Closure $callback)
    {
        $channelId = $this->channelId($event);

        if (!array_key_exists($channelId, $this->listeners)) {
            $this->listeners[$channelId] = new Set();
        }

        $this->listeners[$channelId]->add($callback);
    }

    /**
     * @param string $event
     * @return string
     */
    private function createEvent(string $event) : string
    {
        $key = substr(md5(microtime(true) . random_int(0, 99999)), 0, 8);
        $this->database->command('rpush', [$this->channelId($event), $key]);
        return $key;
    }

    /**
     * @param string $name
     * @return mixed
     */
    private function channelId(string $name)
    {
        return md5($name);
    }

    /**
     * @param $data
     * @return mixed
     */
    private function serialize($data)
    {
        return serialize($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    private function unserialize($data)
    {
        try {
            return unserialize($data);
        } catch (\Throwable $e) {
            return $data;
        }
    }
}