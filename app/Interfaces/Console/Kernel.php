<?php
namespace Interfaces\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Interfaces\Console\Commands\SqlLogger::class,
        \Interfaces\Console\Commands\StartGitterPool::class,
        \Interfaces\Console\Commands\GitterBot::class,
        \Interfaces\Console\Commands\GitterSync::class,
        \Interfaces\Console\Commands\GitterKarmaRecount::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule
            ->command('gitter:pool', ['restart'])
            ->everyFiveMinutes();
    }
}
