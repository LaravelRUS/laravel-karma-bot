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

use App\Gitter\Support\AttributeMapper;

/**
 * Class User
 * @package App\Gitter\Models
 *
 * @property-read string $id
 * @property-read string $login
 * @property-read string $name
 * @property-read string $url
 * @property-read string $avatar
 */
class User extends Model
{
    /**
     * @param array $attributes
     * @return array
     * @throws \InvalidArgumentException
     */
    public function format(array $attributes): array
    {
        return (new AttributeMapper($attributes))
            ->rename('username', 'login')
            ->rename('displayName', 'name')
            ->rename('avatarUrlMedium', 'avatar')
            ->only(['id', 'login', 'name', 'avatar', 'url'])
            ->toArray();
    }
}
