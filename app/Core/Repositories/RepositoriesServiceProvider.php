<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 16:21
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories;

use Core\Repositories\Karma\EloquentKarmaRepository;
use Core\Repositories\Karma\KarmaRepository;
use Core\Repositories\Message\EloquentMessagesRepository;
use Core\Repositories\Message\MessagesRepository;
use Core\Repositories\Room\EloquentRoomsRepository;
use Core\Repositories\Room\RoomsRepository;
use Core\Repositories\Services\ServiceRepository;
use Core\Repositories\User\EloquentMentionsRepository;
use Core\Repositories\User\EloquentUsersRepository;
use Core\Repositories\User\MentionsRepository;
use Core\Repositories\User\UsersRepository;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Core\Repositories\Services\GitterServiceRepository;

/**
 * Class RepositoriesServiceProvider
 * @package Core\Repositories
 */
class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        // === SERVICES === //

        $this->app->singleton(GitterServiceRepository::class, function (Container $app) {
            return new GitterServiceRepository();
        });

        // === OTHER === //

        $this->app->singleton(UsersRepository::class, function(Container $app) {
            return new EloquentUsersRepository();
        });

        $this->app->singleton(MessagesRepository::class, function(Container $app) {
            return new EloquentMessagesRepository();
        });
        
        $this->app->singleton(MentionsRepository::class, function(Container $app) {
            return new EloquentMentionsRepository();
        });

        $this->app->singleton(RoomsRepository::class, function(Container $app) {
            return new EloquentRoomsRepository();
        });
        
        $this->app->singleton(KarmaRepository::class, function(Container $app) {
            return new EloquentKarmaRepository();
        });
    }
}