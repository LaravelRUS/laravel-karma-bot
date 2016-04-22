<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 16:56
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\User;

use Core\Repositories\Repository;
use Core\Repositories\Support\Eloquent;
use Domains\User\User;

/**
 * Class EloquentUsersRepository
 * @package Core\Repositories\User
 */
class EloquentUsersRepository extends Repository implements
    UsersRepository
{
    use Eloquent;

    /**
     * EloquentUsersRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(User::class);
    }
}