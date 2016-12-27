<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Providers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use KarmaBot\Bot\Middleware\Manager;

/**
 * Class BotMiddlewareServiceProvider
 * @package KarmaBot\Providers
 */
class BotMiddlewareServiceProvider extends ServiceProvider
{
    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    public function register(): void
    {
        $this->app->singleton(Manager::class, function (Container $app) {
            $config = $app->make(Repository::class)->get('bot.middleware');

            $manager = new Manager($app);

            foreach ($config as $class) {
                $manager->register($class);
            }


            return $manager;
        });
    }
}
