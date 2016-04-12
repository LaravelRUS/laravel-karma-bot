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
     * @param string $entityClass
     * @return Entity
     */
    public static function create($data, string $entityClass = null) : Entity
    {
        $credinals = new Credinals($data->username, '');

        $entity = $entityClass ?: Entity::class;

        $user = new $entity($credinals, $data->displayName, $data->avatarUrlMedium);

        Builder::fill($user, 'id', $data->id);

        return $user;
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


        return $user;
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
