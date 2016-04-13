<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 13.04.2016 15:21
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io;

/**
 * Interface Request
 * @package Core\Io
 */
interface Request
{
    /**
     * @param \Closure $closure
     * @return $this|Request
     */
    public function onMessage(\Closure $closure) : Request;

    /**
     * @param \Closure $closure
     * @return Request
     */
    public function onUser(\Closure $closure) : Request;

    /**
     * @param \Closure $closure
     * @return Request
     */
    public function onRoom(\Closure $closure) : Request;
}
