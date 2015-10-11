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

namespace App\Console\Commands;


use App\Room;
use App\Gitter\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;


/**
 * Class StartGitterBot
 * @package App\Console\Commands
 */
class StartGitterBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitter:listen {room}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start gitter bot thread for target room.';


    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $pid;


    /**
     * Execute the console command.
     *
     * @param Repository $config
     * @param Container $container
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \Exception
     */
    public function handle(Repository $config, Container $container)
    {
        $this->makePidFile();

        $started = Carbon::now();
        $client  = Client::make($config->get('gitter.token'), $this->argument('room'));
        $stream  = $container->make(Room::class)->listen();

        $this->line(sprintf(' Gitter Bot %s started at %s', Client::VERSION, $started->toDateTimeString()));

        $client->getEventLoop()->addPeriodicTimer(1, function() use ($started) {
            $memory = number_format(memory_get_usage(true) / 1000 / 1000, 2);
            $uptime = Carbon::now()->diff($started);

            $this->output->write("\r" . sprintf(
                '[memory: %smb] [uptime: %s]%60s',
                $memory,
                $uptime->format('%Y.%M.%D %H:%I:%S'),
                ''
            ));
        });

        $client->run();

        $this->removePidFile();
    }

    /**
     * Create pid file
     */
    protected function makePidFile()
    {
        $this->pid = storage_path(date('Y_m_d_tis.pid'));
        file_put_contents($this->pid, getmypid());
    }

    /**
     * Delete pid file
     */
    protected function removePidFile()
    {
        if (is_file($this->pid)) {
            unlink($this->pid);
        }
    }
}
