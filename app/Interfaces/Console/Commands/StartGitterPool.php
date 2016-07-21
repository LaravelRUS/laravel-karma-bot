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
use Symfony\Component\Finder\Finder;

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
    protected $signature = 'gitter:pool {action=start}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start gitter chat pool.';


    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Repository
     */
    protected $config;

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
        $this->container = $container;
        $this->config = $config;


        $action = $this->argument('action');
        switch ($action) {
            case 'start':
            case 'restart':
                $this->stop();
                $this->start();
                break;
            case 'stop':
                $this->stop();
                break;
            default:
                throw new \InvalidArgumentException('Action ' . $action . ' not found');
        }
    }

    /**
     * Start processes
     */
    protected function start()
    {
        foreach ($this->container['room.manager']->all() as $room) {
            $bg = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'start /min /normal' : 'nohup';

            shell_exec("{$bg} php artisan gitter:listen {$room->id()}");
            $this->line('Starting ' . $room->id() . ' listener.');
        }
    }

    /**
     * Stop all processes
     */
    protected function stop()
    {
        $finder = (new Finder())
            ->files()
            ->name('*.pid')
            ->in(storage_path('pids'));

        foreach ($finder as $file) {
            $pid = file_get_contents($file->getRealpath());
            shell_exec('kill ' . $pid);
            unlink($file->getRealpath());
        }
    }
}
