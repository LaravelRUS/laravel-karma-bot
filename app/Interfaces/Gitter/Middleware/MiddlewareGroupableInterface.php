<?php
/**
 * This file is part of GitterBot package.
 *
 * @author butschster <butschster@gmail.com>
 * @date 20.07.2016 15:34
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Interfaces\Gitter\Middleware;

/**
 * Interface MiddlewareInterface
 */
interface MiddlewareGroupableInterface
{
    /**
     * @return string|array
     */
    public function getGroup();
}
