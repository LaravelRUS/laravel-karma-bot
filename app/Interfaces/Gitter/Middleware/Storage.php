<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @author butschster <butschster@gmail.com>
 *
 * @date 24.09.2015 15:27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Interfaces\Gitter\Middleware;

use Illuminate\Contracts\Container\Container;
use Interfaces\Gitter\Support\PriorityList;

/**
 * Class Storage
 */
class Storage
{
    const PRIORITY_MINIMAL = 1; // Lower priority
    const PRIORITY_DEFAULT = 2;
    const PRIORITY_MAXIMAL = 3; // Maximal priority (?)


    // Middleware response for stopping iterations
    const SIGNAL_STOP = null;


    /**
     * @var MiddlewareInterface[]|PriorityList
     */
    protected $storage;


    /**
     * @var Container
     */
    protected $container;


    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->storage = new PriorityList();
    }

    /**
     * @param string $class
     * @param int $priority
     *
     * @return Storage
     * @throws \Exception
     */
    public function add($class, $priority = self::PRIORITY_DEFAULT): Storage
    {
        $this->container->bind($class, $class);
        $instance = $this->container->make($class);

        if (! ($instance instanceof MiddlewareInterface)) {
            throw new \Exception("Class [{$class}] must be instance of Interfaces\\Gitter\\Middleware\\MiddlewareInterface");
        }

        if ($instance instanceof MiddlewareGroupableInterface and ! $this->checkMiddlewareGroup($instance)) {
            return $this;
        }

        $this->storage->insert($instance, $priority);

        return $this;
    }


    /**
     * @param Model|mixed $data
     * @return Storage
     */
    public function handle($data): Storage
    {
        foreach ($this->storage as $middleware) {
            $response = $middleware->handle($data);

            if ($response === static::SIGNAL_STOP) {
                return $this;
            }

            // Update data
            $data = $response;
        }

        return $this;
    }

    /**
     * @param MiddlewareGroupableInterface $middleware
     *
     * @return bool
     */
    protected function checkMiddlewareGroup(MiddlewareGroupableInterface $middleware)
    {
        $groups = (array) $middleware->getGroup();
        $currentGroups = \Config::get('gitter.env');

        if (is_string($currentGroups)) {
            $currentGroups = array_map(function ($item) {
                return trim($item);
            }, explode(',', $currentGroups));
        }

        if (! is_array($currentGroups)) {
            return true;
        }

        foreach ($currentGroups as $group) {
            if (in_array($group, $groups)) {
                return true;
            }
        }

        return false;
    }
}
