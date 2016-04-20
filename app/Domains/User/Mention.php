<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 2:02
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\User;

use Core\Mappers\User\MentionMapper;

/**
 * Class Mention
 * @package Domains\User
 */
class Mention extends MentionMapper
{
    /**
     * @param User $user
     * @return bool
     */
    public function isMentionOf(User $user) : bool
    {
        return $this->target->id === $user->id;
    }
}
