<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 15:27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Interfaces\Console\Commands;


use Carbon\Carbon;
use Core\Doctrine\SqlMemoryLogger;
use Core\Io\IoInterface;
use Doctrine\ORM\EntityManagerInterface;
use Domains\Achieve\AchieveInterface;
use Domains\Bot\Middlewares;
use Domains\Message\Message;
use Domains\Room\Room;
use Domains\User\User;
use Gitter\Client;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Application;


/**
 * Class Bot
 */
class Bot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start bot process.';

    /**
     * @var string
     */
    protected $pid;

    /**
     * @param Container $container
     * @param IoInterface $io
     */
    public function handle(Container $container, IoInterface $io)
    {
        $io->onAuth(function($data) {
            var_dump('AUTH', $data);
        });

        $io->entity(User::class)->listen('created', function($data) {
            var_dump('User::created', $data);
        });

        $io->run();
    }
}
