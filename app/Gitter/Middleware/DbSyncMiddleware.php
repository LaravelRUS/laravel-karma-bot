<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 15:47
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Middleware;

/**
 * Class DbSyncMiddleware
 * @package App\Gitter\Middleware
 */
class DbSyncMiddleware implements MiddlewareInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function handle($data)
    {
        return $data;
    }
}
