<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.10.2015 22:50
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Interfaces\Console\Commands\Support\Process;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;


/**
 * Class StartGitterPool
 */
class StartGitterPool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitter:pool';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start gitter chat pool.';

    /**
     * @var array|Process[]
     */
    private $processes = [];

    /**
     * @var bool
     */
    private $disposed = false;

    /**
     * Execute the console command.
     *
     * @param Repository $config
     * @param Container $container
     */
    public function handle(Repository $config, Container $container)
    {
        /** @var LoopInterface $loop */
        $loop = Factory::create();

        $this->call('doctrine:generate:proxies');

        $rooms = $config->get('gitter.rooms');


        foreach ($rooms as $room => $middlewares) {
            $process = new Process('gitter:listen ' . $room);

            $this->processes[] = $process;

            $process->start();

            $this->info('Starting process ' . $process->getCommand());
        }


        $loop->addPeriodicTimer(1, function () {
            foreach ($this->processes as $process) {
                if (!$process->isRunning()) {
                    $this->error('Process ' . $process->getCommand() . ' was be shutting down. Restarting');
                    $process->start();
                }
            }
        });

        register_shutdown_function([$this, 'dispose']);

        $loop->run();
    }

    /**
     * @return void
     */
    public function dispose()
    {
        if (!$this->disposed) {
            foreach ($this->processes as $process) {
                if ($process->isRunning()) {
                    $process->stop();
                }
            }
            $this->disposed = true;
        }
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->dispose();
    }
}
