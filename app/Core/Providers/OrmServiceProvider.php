<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.04.2016 3:51
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Providers;

use Core\Observers\IdObserver;
use Domains\Message\Message;
use Domains\Message\Relation;
use Domains\Message\Url;
use Domains\User\Mention;
use Illuminate\Support\ServiceProvider;

/**
 * Class OrmServiceProvider
 * @package Core\Providers
 */
class OrmServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        // Do nothing
    }

    /**
     * @return void
     */
    public function boot()
    {
        Url::observe(IdObserver::class);
        Message::observe(IdObserver::class);
        Message::observe(IdObserver::class);
        Mention::observe(IdObserver::class);
        Relation::observe(IdObserver::class);
    }
}