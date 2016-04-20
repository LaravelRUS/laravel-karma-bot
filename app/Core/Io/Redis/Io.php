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

/**
 * Class Io
 * @package Core\Io\Redis
 */
class Io implements IoInterface
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @var array|EntityInterface[]|Entity[]
     */
    private $entities = [];

    /**
     * Io constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param string $name
     * @return EntityInterface
     */
    public function entity(string $name) : EntityInterface
    {
        if (!array_key_exists($name, $this->entities)) {
            $this->entities[$name] = new Entity($this->database, $name);
        }

        return $this->entities[$name];
    }

    /**
     * @param \Closure $callback
     * @return $this|IoInterface
     */
    public function onAuth(\Closure $callback) : IoInterface
    {
        $this->database->subscribe('auth', function($data) use ($callback) {
            $callback(unserialize($data));
        });

        return $this;
    }

    /**
     * @param User $user
     * @return IoInterface
     */
    public function auth(User $user) : IoInterface
    {
        $this->database->command('publish', ['auth', serialize($user)]);

        return $this;
    }
}