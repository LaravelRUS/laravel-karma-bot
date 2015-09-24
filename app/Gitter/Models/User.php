<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 00:00
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Models;


/**
 * Class User
 * @package App\Gitter\Models
 */
class User extends Model
{
    /**
     * @var array
     */
    protected static $users = [];

    /**
     * @param $userData
     */
    public static function findOrCreate($userData)
    {
        $id = $userData['id'];
        if (!array_key_exists($id, static::$users)) {
            static::$users[$id] = new static($userData);
        }

        return static::$users[$id];
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function format(array $attributes)
    {
        return [
            'gitter_id' => $attributes['id'],
            'login'     => $attributes['username'],
            'name'      => $attributes['displayName'],
            'url'       => $attributes['url'],
            'avatar'    => $attributes['avatarUrlMedium'],
        ];
    }
}
