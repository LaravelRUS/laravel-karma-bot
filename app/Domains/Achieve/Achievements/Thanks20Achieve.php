<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.10.2015 6:07
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Achieve\Achievements;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Domains\Achieve\Achieve;
use Domains\Achieve\AchieveInterface;
use Domains\Achieve\Meta\Event;
use Domains\User\User;

/**
 * Class Thanks20Achieve
 * @package Domains\Achieve\Achievements
 * @ORM\Entity
 * @ORM\Table(name="achievements")
 * @ORM\AttributeOverrides({})
 */
class Thanks20Achieve extends Achieve implements AchieveInterface
{
    /**
     * @param LifecycleEventArgs $event
     * @Event(name=Core\Doctrine\Events::POST_PERSIST, entity=Karma::class)
     * @return User|void
     */
    public static function onKarma(LifecycleEventArgs $event)
    {
        /** @var User $user */
        $user = $event->getEntity()->user;

        if ($user->thanks->count() >= 20) {
            return $user;
        }
    }

    /**
     * @return int
     */
    public function getType() : int
    {
        return static::PERMANENT;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return 'Благодарный';
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'Высказать 20 благодарностей.';
    }

    /**
     * @return string
     */
    public function getImage() : string
    {
        return '//karma.laravel.su/img/achievements/thanks-20.gif';
    }
}
