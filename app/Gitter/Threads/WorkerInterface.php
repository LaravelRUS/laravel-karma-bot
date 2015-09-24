<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 17:09
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Threads;

/**
 * Interface WorkerInterface
 * @package App\Gitter\Threads
 */
interface WorkerInterface
{
    /**
     * @return mixed
     */
    public function run();
}
