<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.10.2015 8:30
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Subscribers;

use App\Room;
use App\Achieve;
use App\Gitter\Subscriber\SubscriberInterface;
use Illuminate\Config\Repository;

/**
 * Class AchieveSubscriber
 * @package App\Subscribers
 */
class AchieveSubscriber implements SubscriberInterface
{
    /**
     * AchieveSubscriber constructor.
     */
    public function __construct(Repository $repository)
    {
        $achievements = $repository->get('gitter.achievements');
        foreach ($achievements as $achieve) {
            $instance = new $achieve;
            $instance->handle();
        }
    }

    /**
     * Subscribe achievements
     */
    public function handle()
    {
        Achieve::created(function (Achieve $achieve) {
            $room = \App::make(Room::class);

            $room->write(
                '> #### ' . $achieve->title . "\n" .
                '> *Поздравляем тебя @' . $achieve->user->login . '! ' .
                $achieve->description . '*' . "\n" .
                '> ![' . $achieve->title . '](' . $achieve->image . ')'
            );
        });
    }
}