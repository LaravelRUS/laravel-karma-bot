<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Model\Transformer;

use App\Model\System;
use App\Model\User;
use Serafim\KarmaCore\Io\UserInterface;

/**
 * Class UserTransformer
 * @package KarmaBot\Model\Transformer
 * @mixin User
 */
trait UserTransformer
{
    /**
     * @param UserInterface $user
     * @return static|User|UserTransformer
     */
    public static function new(UserInterface $user): User
    {
        $model = new static();

        $model->name = $user->getName();
        $model->login = $user->getName();

        return $model;
    }
}
