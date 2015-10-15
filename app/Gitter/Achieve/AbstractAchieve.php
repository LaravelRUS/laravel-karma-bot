<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.10.2015 6:09
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Gitter\Achieve;

use App\User;
use App\Achieve;
use App\Gitter\Subscriber\SubscriberInterface;

abstract class AbstractAchieve implements
    AchieveInterface,
    SubscriberInterface
{
    /**
     * Achieve title
     * @var string
     */
    public $title = 'undefined';

    /**
     * Achieve description
     * @var string
     */
    public $description = 'undefined';

    /**
     * Achieve image link
     * @var string
     */
    public $image = '/img/achievements/karma-10.gif';

    /**
     * @param User $user
     * @return static
     * @throws \LogicException
     */
    public function create(User $user)
    {
        $achieve = Achieve::create([
            'name'        => static::class,
            'user_id'     => $user->id
        ]);

        return $achieve;
    }
}