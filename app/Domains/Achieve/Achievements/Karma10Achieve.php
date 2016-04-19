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

use Core\Doctrine\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Domains\Achieve\AchieveInterface;
use Domains\Achieve\Meta\Event;
use Domains\Achieve\Achieve;
use Domains\Karma\Karma;
use Domains\User\User;

/**
 * Class Karma10Achieve
 */
class Karma10Achieve extends Achieve implements AchieveInterface
{
    /**
     * @return string
     */
    public function getTitle() : string
    {
        return 'Находчивый';
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'Набрать 10 кармы.';
    }

    /**
     * @return string
     */
    public function getImage() : string
    {
        return '//karma.laravel.su/img/achievements/karma-10.gif';
    }

    /**
     * @param LifecycleEventArgs $event
     * @Event(name=Events::POST_PERSIST, entity=Karma::class)
     * @return User|void
     */
    public static function onKarma(LifecycleEventArgs $event)
    {
        /** @var Karma $entity */
        $entity = $event->getEntity();

        /** @var User $user */
        $user = $entity->target;

        $count = $user->karma->count();

        if ($count >= 1) {
            return $user;
        }
    }
}
