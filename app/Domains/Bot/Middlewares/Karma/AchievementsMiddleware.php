<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 19.04.2016 0:06
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot\Middlewares\Karma;

use Core\Doctrine\Events;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventArgs;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Domains\Achieve\Achieve;
use Domains\Achieve\AchieveInterface;
use Domains\Achieve\Achievements;
use Domains\Achieve\Meta\Event;
use Domains\Bot\Middlewares\Middleware;
use Domains\User\User;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;

/**
 * Class AchievementsMiddleware
 * @package Domains\Bot\Middlewares\Karma
 */
class AchievementsMiddleware implements Middleware
{
    /**
     * @var array|string[]
     */
    private $achievements = [];

    /**
     * @var Container
     */
    private $app;

    /**
     * AchievementsMiddleware constructor.
     * @param Container $app
     * @param Events $events
     * @param Reader $reader
     */
    public function __construct(Container $app, Events $events, Reader $reader)
    {
        $this->achievements = $this->getAchievements($app);
        /** @var string $achieve */
        foreach ($this->achievements as $achieve) {
            $this->registerAchieveEvents($events, $reader, $achieve);
        }
        $this->app = $app;
    }

    /**
     * @param Container $app
     * @return array|string[]
     */
    private function getAchievements(Container $app) : array
    {
        return [
            Achievements\Karma10Achieve::class,
            Achievements\Karma50Achieve::class,
            Achievements\Karma100Achieve::class,
            Achievements\Karma500Achieve::class,
            Achievements\Thanks20Achieve::class,
            Achievements\Thanks50Achieve::class,
            Achievements\Thanks100Achieve::class,
            Achievements\Thanks10Karma0Achieve::class,
            Achievements\DocsAchieve::class,
        ];
    }

    /**
     * @param Events $events
     * @param Reader $reader
     * @param string $achieve
     */
    private function registerAchieveEvents(Events $events, Reader $reader, string $achieve)
    {
        $methods = (new \ReflectionClass($achieve))
            ->getMethods(\ReflectionMethod::IS_STATIC | \ReflectionMethod::IS_PUBLIC);

        /** @var \ReflectionMethod $method */
        foreach ($methods as $method) {

            /** @var Event $event */
            $event = $reader->getMethodAnnotation($method, Event::class);
            if ($event) {
                $this->registerEvent($method, $event, $events);
            }
        }
    }

    /**
     * @param \ReflectionMethod $method
     * @param Event $event
     * @param Events $events
     */
    private function registerEvent(\ReflectionMethod $method, Event $event, Events $events)
    {
        $events->subscribe($event->name, function (EventArgs $args) use ($method, $event) {

            $validEntity = $event->entity === null || (
                    $args instanceof LifecycleEventArgs &&
                    $event->entity &&
                    $args->getEntity() instanceof $event->entity
                );

            if ($validEntity) {
                $context = [$method->getDeclaringClass()->name, $method->name];

                $user = $this->app->call($context, ['event' => $args]);
                if ($user && $user instanceof User) {
                    $achieve = $this->app->make($method->getDeclaringClass()->name, [
                        'user' => $user
                    ]);

                    /** @var Dispatcher $events */
                    $events = $this->app->make('events');
                    $events->fire(AchieveInterface::EVENT_ADD, [
                        'achieve' => $achieve,
                        'user'    => $user,
                    ]);
                }
            }
        });
    }
}