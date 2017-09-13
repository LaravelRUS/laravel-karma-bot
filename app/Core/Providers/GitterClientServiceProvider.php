<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @author butschster <butschster@gmail.com>
 * @date 09.04.2016 3:09
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Providers;

use Domains\BotManager;
use Domains\RoomManager;
use Gitter\Client;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Interfaces\Gitter\StandartGitterRoom;
use Interfaces\Slack\StandartSlackRoom;

/**
 * Class GitterClientServiceProvider
 * @package Core\Providers
 */
class GitterClientServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(Client::class, function (Container $app) {
            return new Client($app['config']->get('gitter.token'));
        });

        $this->app->singleton('bot', function (Container $app) {
            /** @var Client $client */
            $client = $app->make(Client::class);
            return $client->http->getCurrentUser()->wait();
        });
    }

    public function boot()
    {
        $this->app->singleton('bot.manager', function (Container $app) {
            return new BotManager($app);
        });

        $this->app->singleton('room.manager', function (Container $app) {
            $manager = new RoomManager();

            $this->registerGitterRooms($manager);
            $this->registerSlackRooms($manager);

            return $manager;
        });
    }

    /**
     * @param RoomManager $manager
     */
    protected function registerGitterRooms(RoomManager $manager)
    {
        foreach ((array) $this->app['config']->get('gitter.rooms') as $room => $groups) {
            if (empty($room)) {
                continue;
            }

            $manager->register(
                new StandartGitterRoom($room, $groups, \Config::get('gitter.middlewares'))
            );
        }
    }

    /**
     * @param RoomManager $manager
     */
    protected function registerSlackRooms(RoomManager $manager)
    {
        foreach ((array) $this->app['config']->get('slack.rooms') as $roomId => $groups) {
            if (empty($roomId)) {
                continue;
            }

            $manager->register(
                new StandartSlackRoom($roomId, $groups, \Config::get('slack.middlewares'))
            );
        }
    }
}