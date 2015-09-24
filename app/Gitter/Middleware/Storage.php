<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 15:27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Middleware;

use App\Gitter\Models\Model;
use Illuminate\Container\Container;

/**
 * Class Storage
 * @package App\Gitter\Middleware
 */
class Storage
{
    const PRIORITY_MINIMAL = 1; // Lower priority
    const PRIORITY_DEFAULT = 2;
    const PRIORITY_MAXIMAL = 3; // Maximal priority (?)


    // Middleware response for stopping iterations
    const SIGNAL_STOP = null;


    /**
     * @var MiddlewareInterface[]|\SplPriorityQueue
     */
    protected $storage;


    /**
     * @var Container
     */
    protected $container;


    /**
     * @param Container $container
     * @return Storage
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->storage = new \SplPriorityQueue();
    }


    /**
     * @param string $class
     * @param int $priority
     * @return Storage
     */
    public function add($class, $priority = self::PRIORITY_DEFAULT): Storage
    {
        $this->container->singleton($class, $class);
        $instance = $this->container->make($class);

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
}
