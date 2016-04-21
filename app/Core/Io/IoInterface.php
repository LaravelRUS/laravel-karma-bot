<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 14:48
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io;

use Domains\User\User;

/**
 * Interface IoInterface
 * @package Core\Io
 */
interface IoInterface
{
    /**
     * @param string $name
     * @return EntityInterface
     */
    public function entity(string $name) : EntityInterface;

    /**
     * @param \Closure $callback
     * @return IoInterface
     */
    public function onAuth(\Closure $callback) : IoInterface;

    /**
     * @param User $user
     * @return IoInterface
     */
    public function auth(User $user) : IoInterface;
}