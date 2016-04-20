<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 14:50
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface EntityInterface
 * @package Core\Io
 */
interface EntityInterface
{
    /**
     * @param string $event
     * @param \Closure $callback
     * @return $this|EntityInterface
     */
    public function listen(string $event, \Closure $callback) : EntityInterface;

    /**
     * @param string $event
     * @param Model $entity
     * @return $this|EntityInterface
     */
    public function fire(string $event, Model $entity) : EntityInterface;
}
