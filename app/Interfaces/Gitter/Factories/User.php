<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 17:41
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Gitter\Factories;

use Domains\User\User as Entity;
use Domains\User\Credinals;

/**
 * Class UserMapper
 * @package Interfaces\Gitter\Factories
 */
class User
{
    /**
     * @param \StdClass $data
     * @return Entity
     */
    public static function create($data) : Entity
    {
        $credinals = new Credinals($data->username, '');

        $user = new Entity($credinals, $data->displayName, $data->avatarUrlMedium);
        $user->gitterId = $data->id;

        return $user;
    }
}
