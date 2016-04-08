<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 15:34
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Middleware;

use Domains\Message;

/**
 * Interface MiddlewareInterface
 * @package App\Gitter\Middleware
 */
interface MiddlewareInterface
{
    /**
     * @param $inputData
     * @return mixed
     */
    public function handle(Message $inputData);
}
