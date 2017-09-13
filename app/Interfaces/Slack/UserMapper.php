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

namespace Interfaces\Slack;

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
    public static function fromSlackObject(\Slack\User $user)
    {
        $values = (new AttributeMapper((array) $user->data))
            ->rename('id', 'gitter_id')
            ->rename('name', 'login')
            ->rename('real_name', 'name')
            ->value('profile', function($profile) {
                return $profile['image_48'];
            }, 'avatar')
            ->only(['gitter_id', 'login', 'name', 'avatar'])
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
