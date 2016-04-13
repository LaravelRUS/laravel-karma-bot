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

use Core\Entity\Builder;
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

        Builder::fill($user, 'id', $data->id);

        return Builder::synchronized($user);
    }

    /**
     * @param $data
     * @return Entity
     */
    public static function createFromMention($data) : Entity
    {
        $credinals = new Credinals($data->screenName, '');

        $user = new Entity($credinals, $data->screenName);

        Builder::fill($user, 'id', $data->userId);

        return Builder::synchronized($user);
    }

    /**
     * @param $data
     * @return bool
     */
    public static function isValidMention($data) : bool
    {
        return property_exists($data, 'userId');
    }
}
