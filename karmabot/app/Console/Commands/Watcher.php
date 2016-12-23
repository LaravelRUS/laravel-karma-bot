<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use React\EventLoop\LoopInterface;

/**
 * Class Watcher
 * @package KarmaBot\Console\Commands
 */
class Watcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch files';

    /**
     * Execute the console command.
     *
     * @param Application $app
     * @param LoopInterface $loop
     * @return mixed
     */
    public function handle(Application $app, LoopInterface $loop): void
    {
        if ($app->environment() === 'local') {
            $loop->addPeriodicTimer(2, function () {
                $this->call('ide-helper:models', ['--nowrite' => true]);
            });
        }


        $loop->run();
    }
}
