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

namespace Core\Mappers;

use Domains\User;
use InvalidArgumentException;
use App\Gitter\Support\AttributeMapper;

/**
 * Class UserMapperTrait
 * @package App\Mappers
 */
trait UserMapperTrait
{
    /**
     * @TODO Add User::class memory pool (with GC probably)
     *
     * @param array $attributes
     * @return User
     * @throws InvalidArgumentException
     */
    public static function fromGitterObject(array $attributes)
    {
        $values = (new AttributeMapper($attributes))
            ->rename('id', 'gitter_id')
            ->rename('username', 'login')
            ->rename('displayName', 'name')
            ->rename('avatarUrlMedium', 'avatar')
            ->only(['gitter_id', 'login', 'name', 'avatar', 'url'])
            ->toArray();

        $user = static::where('gitter_id', $values['gitter_id'])->first();
        if (!$user) {
            $user = static::unguarded(function () use ($values) {
                return static::create($values);
            });
        }

        return $user;
    }
}
