<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 22.04.2016 16:58
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\Support\Eloquent;

/**
 * Class Transaction
 * @package Core\Repositories\Support
 */
trait Transaction
{
    /**
     * @param \Closure $callback
     * @return mixed
     */
    protected function transaction(\Closure $callback)
    {
        return \DB::transaction($callback);
    }
}