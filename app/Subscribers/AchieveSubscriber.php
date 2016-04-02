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
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use App\Gitter\Subscriber\SubscriberInterface;
use App\Subscribers\Achievements\Karma10Achieve;
use App\Subscribers\Achievements\Karma50Achieve;
use App\Subscribers\Achievements\Karma100Achieve;
use App\Subscribers\Achievements\Karma500Achieve;
use App\Subscribers\Achievements\Thanks20Achieve;
use App\Subscribers\Achievements\Thanks50Achieve;
use App\Subscribers\Achievements\Thanks100Achieve;
use App\Subscribers\Achievements\Thanks10Karma0Achieve;
use App\Subscribers\Achievements\DocsAchieve;

/**
 * Class AchieveSubscriber
 * @package App\Subscribers
 */
class AchieveSubscriber implements
    SubscriberInterface,
    Arrayable,
    Jsonable
{
    /**
     * @var array
     */
    protected $achievements = [
        Karma10Achieve::class,
        Karma50Achieve::class,
        Karma100Achieve::class,
        Karma500Achieve::class,
        Thanks20Achieve::class,
        Thanks50Achieve::class,
        Thanks100Achieve::class,
        Thanks10Karma0Achieve::class,
        DocsAchieve::class
    ];

    /**
     * @var array
     */
    protected $instances = [];

    /**
     * AchieveSubscriber constructor.
     */
    public function __construct()
    {
        foreach ($this->achievements as $achieve) {
            $this->instances[] = ($instance = new $achieve);
            $instance->handle();
        }
    }

    /**
     * @return array
     */
    public function getAchievementInstances(): array
    {
        return $this->instances;
    }

    /**
     * Subscribe achievements
     */
    public function handle()
    {
        Achieve::created(function (Achieve $achieve) {
            $room = \App::make(Room::class);

            $room->write(
                \Lang::get('achieve.receiving', [
                    'user'        => $achieve->user->login,
                    'title'       => $achieve->title,
                    'description' => $achieve->description,
                    'image'       => $achieve->image,
                ])
            );
        });
    }

    /**
     * @return Collection
     */
    public function toCollection(): Collection
    {
        return new Collection($this->getAchievementInstances());
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this
            ->toCollection()
            ->toArray();
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}