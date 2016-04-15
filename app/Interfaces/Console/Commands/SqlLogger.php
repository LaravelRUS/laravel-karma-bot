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


use Core\Doctrine\Memory;
use Core\Doctrine\SqlMemoryLogger;
use Domains\Bot\Middlewares;
use Illuminate\Console\Command;
use React\EventLoop\Factory;


/**
 * Class SqlLogger
 */
class SqlLogger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:sql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start memory logger';

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Throwable
     * @throws \Exception
     */
    public function handle()
    {
        $path = SqlMemoryLogger::MEMORY_SYNC_PATH;
        $key = SqlMemoryLogger::MEMORY_KEY;

        $memory = (new Memory(storage_path($path)))->open();

        $loop = Factory::create();

        $loop->addPeriodicTimer(1, function () use ($key, $memory) {
             $memory->lock();
             $data = $memory->get($key);

            foreach ($data as $query) {
                $this->info(' > ' . $query . "\n");
            }

            $memory->set($key, []);
            $memory->unlock();
        });

        $loop->run();
    }
}
