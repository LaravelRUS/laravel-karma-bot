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
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
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
        /** @var Repository $config */
        $config = $this->app->make(Repository::class);

        $this->app->singleton(Client::class, function (Container $app) use ($config) {
            return new Client($config->get('gitter.token'));
        });

        $this->app->singleton('bot', function (Container $app) {
            /** @var Client $client */
            $client = $app->make(Client::class);

            return $client->http->getCurrentUser()->wait();
        });
    }

    public function boot()
    {
        /** @var Repository $config */
        $config = $this->app->make(Repository::class);

        $this->app->singleton(BotManager::class, function (Application $app) {
            return new BotManager($app);
        });

        $this->app->singleton(RoomManager::class, function (Container $app) use ($config) {
            $manager = new RoomManager();

            $this->registerGitterRooms($manager, $config);
            $this->registerSlackRooms($manager, $config);

            return $manager;
        });
    }

    /**
     * @param RoomManager $manager
     * @param Repository $config
     */
    protected function registerGitterRooms(RoomManager $manager, Repository $config)
    {
        foreach ((array)$config->get('gitter.rooms') as $room => $groups) {
            if (!$room) {
                continue;
            }

            $manager->register(
                new StandartGitterRoom($room, $groups, $config->get('gitter.middlewares'))
            );
        }
    }

    /**
     * @param RoomManager $manager
     * @param Repository $config
     */
    protected function registerSlackRooms(RoomManager $manager, Repository $config)
    {
        foreach ((array)$config->get('slack.rooms') as $roomId => $groups) {
            if (!$roomId) {
                continue;
            }

            $manager->register(
                new StandartSlackRoom($roomId, $groups, $config->get('slack.middlewares'))
            );
        }
    }
}