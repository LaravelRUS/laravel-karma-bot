<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 16:55
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\User;
use Domains\User\User;

/**
 * Interface UsersRepository
 * @package Core\Repositories\User
 */
interface UsersRepository
{
    /**
     * @param string $id
     * @return User|null
     */
    public function find($id);
}
