<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 13.04.2016 16:02
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io;

use Domains\User\User;

/**
 * Interface Authentication
 * @package Core\Io
 */
interface Authentication
{
    /**
     * @return User
     */
    public function auth() : User;
}
