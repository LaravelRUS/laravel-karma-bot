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

use App\Gitter\Client;
use App\Gitter\Support\AttributeMapper;

/**
 * Class UserObject
 * @package App\Gitter\Models
 *
 * @property string $gitter_id
 * @property string $login
 * @property string $name
 * @property string $url
 * @property string $avatar
 */
class UserObject extends Model
{
    /**
     * @param array $attributes
     * @return array
     * @throws \InvalidArgumentException
     */
    public function format(array $attributes): array
    {
        return (new AttributeMapper($attributes))
            ->rename('id', 'gitter_id')
            ->rename('username', 'login')
            ->rename('displayName', 'name')
            ->rename('avatarUrlMedium', 'avatar')
            ->only(['gitter_id', 'login', 'name', 'avatar', 'url'])
            ->toArray();
    }

    /**
     * @param $text
     * @return $this
     */
    public function answer($text)
    {
        $text = sprintf('@%s %s', $this->login, $text);

        return parent::answer($text);
    }
}
