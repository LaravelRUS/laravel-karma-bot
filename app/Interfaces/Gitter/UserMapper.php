<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.10.2015 16:56
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Interfaces\Gitter;

use Domains\User;
use Interfaces\Gitter\Support\AttributeMapper;

/**
 * Class UserMapper
 */
class UserMapper
{
    /**
     * @param array|\StdClass $attributes
     * @return User
     * @throws InvalidArgumentException
     */
    public static function fromGitterObject($attributes)
    {
        $values = (new AttributeMapper((array) $attributes))
            ->rename('id', 'gitter_id')
            ->rename('username', 'login')
            ->rename('displayName', 'name')
            ->rename('avatarUrlMedium', 'avatar')
            ->only(['gitter_id', 'login', 'name', 'avatar', 'url'])
            ->toArray();

        $user = User::where('gitter_id', $values['gitter_id'])->first();
        if (!$user) {
            $user = User::unguarded(function () use ($values) {
                return User::create($values);
            });
        }

        return $user;
    }
}
