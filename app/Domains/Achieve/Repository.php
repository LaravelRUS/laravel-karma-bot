<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 19.04.2016 16:37
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Achieve;

use Core\Doctrine\Events;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventArgs;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Domains\Achieve\Achievements;
use Domains\Achieve\Meta\Event;
use Domains\User\User;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;

/**
 * Class AchievementsMiddleware
 * @package Domains\Achieve
 */
class Repository
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
     * @var Events
     */
    private $events;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * AchievementsMiddleware constructor.
     * @param Container $app
     * @param Events $events
     * @param Reader $reader
     */
    public function __construct(Container $app, Events $events, Reader $reader)
    {
        $this->events = $events;
        $this->reader = $reader;
        $this->app = $app;

        $this->registerDefaultAchievements();
    }

    /**
     * @return void
     */
    private function registerDefaultAchievements()
    {
        $achievements = $this->getDefaultAchievements();
        /** @var string $achieve */
        foreach ($achievements as $achieve) {
            $this->register($achieve);
        }
    }

    /**
     * @return array|string[]
     */
    private function getDefaultAchievements() : array
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
     * @param string $achieve
     * @return $this|Repository
     */
    public function register(string $achieve) : Repository
    {
        $this->achievements[] = $achieve;
        $this->registerAchieveEvents($this->events, $this->reader, $achieve);

        return $this;
    }

    /**
     * @param Events $events
     * @param Reader $reader
     * @param string $achieve
     */
    private function registerAchieveEvents(Events $events, Reader $reader, string $achieve)
    {
        $scope = \ReflectionMethod::IS_STATIC | \ReflectionMethod::IS_PUBLIC;
        $methods = (new \ReflectionClass($achieve))->getMethods($scope);

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
                $user = $this->app->call(
                    [$method->getDeclaringClass()->name, $method->name],
                    ['event' => $args]
                );

                if ($user && $user instanceof User) {
                    $this->fireEvent($method, $user);
                }
            }
        });
    }

    /**
     * @param \ReflectionMethod $method
     * @param User $user
     */
    private function fireEvent(\ReflectionMethod $method, User $user)
    {
        $achieve = $this->app->make($method->getDeclaringClass()->name, [
            'user' => $user,
        ]);

        /** @var Dispatcher $events */
        $events = $this->app->make('events');
        $events->fire(AchieveInterface::EVENT_ADD, [
            'achieve' => $achieve,
            'user'    => $user,
        ]);
    }

    /**
     * @param \Closure $callback
     * @return Repository
     */
    public function subscribe(\Closure $callback) : Repository
    {
        /** @var Dispatcher $events */
        $events = $this->app->make('events');
        $events->listen(AchieveInterface::EVENT_ADD, $callback);

        return $this;
    }
}