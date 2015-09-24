<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 17:08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter;
use App\Gitter\Threads\WorkerInterface;

/**
 * Class Thread
 * @package App\Gitter
 */
class Thread
{
    /**
     * @var \SplObjectStorage
     */
    protected static $pool = null;

    /**
     * @return void
     */
    protected static function boot()
    {
        if (static::$pool === null) {
            static::$pool = new \SplObjectStorage();
        }
    }

    /**
     * @param WorkerInterface $worker
     * @return Thread
     */
    public static function create(WorkerInterface $worker): Thread
    {
        static::boot();

        $thread = new static($worker);
        static::$pool->attach($thread);

        return $thread;
    }

    /**
     * @param WorkerInterface $worker
     */
    protected function __construct(WorkerInterface $worker)
    {
        $worker->run();
    }
}
