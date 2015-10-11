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
     * @var User
     */
    protected $user;

    /**
     * @param User $user
     * @return $this
     */
    public function forUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param $title
     * @param $description
     * @param $image
     * @return static
     * @throws \LogicException
     */
    public function create($title, $description, $image)
    {
        if ($this->user === null) {
            throw new \LogicException('Can not add achieve. User missing.');
        }

        $achieve = Achieve::create([
            'name'        => static::class,
            'user_id'     => $this->user->id,
            'title'       => $title,
            'description' => $description,
            'image'       => $image,
        ]);

        $this->user = null;

        return $achieve;
    }
}