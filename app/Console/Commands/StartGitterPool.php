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
namespace App\Console\Commands;


use App\Room;
use App\Gitter\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;


/**
 * Class StartGitterPool
 * @package App\Console\Commands
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
        $this->config    = $config;


        $action = $this->argument('action');
        switch ($action) {
            case 'start':
                $this->start();
                break;
            case 'stop':
                $this->stop();
                break;
            case 'restart':
                $this->stop();
                $this->start();
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
        foreach ($this->config->get('gitter.rooms') as $key => $id) {
            system('php artisan gitter:users ' . $key);

            $process = new Process('nohup php artisan gitter:listen ' . $key . ' &');
            $process->run();

            $this->line('Starting ' . $key . ' => ' . $id . ' listener.');
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
            system('kill ' . $pid);
        }
    }
}
