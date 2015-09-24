<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 17:11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Threads;

/**
 * Class Worker
 * @package App\Gitter\Threads
 */
abstract class Worker implements WorkerInterface
{
    /**
     * @var array
     */
    private $args = [];

    /**
     * @param $args
     */
    public function __construct(array $args)
    {
        $this->args = $args;
    }

    /**
     * @return mixed
     */
    abstract public function run();

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->args[$name];
    }
}
