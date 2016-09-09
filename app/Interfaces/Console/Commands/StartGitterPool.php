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


use Domains\Room\RoomInterface;
use Domains\RoomManager;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

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
                $this->stop()
                    ->start($container->make(RoomManager::class));
                break;
            case 'stop':
                $this->stop();
                break;
            default:
                throw new \InvalidArgumentException('Action ' . $action . ' not found');
        }
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return $this
     */
    protected function stop()
    {
        $finder = (new Finder())
            ->files()
            ->name('*.pid')
            ->in(storage_path('pids'))
        ;

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $pid = $file->getContents();
            shell_exec('kill ' . $pid);
            unlink($file->getRealPath());
        }

        return $this;
    }

    /**
     * @param RoomManager $manager
     * @return $this
     */
    protected function start(RoomManager $manager)
    {
        /** @var RoomInterface $room */
        foreach ($manager->all() as $room) {
            shell_exec('nohup php artisan gitter:listen ' . $room->id() . ' > /dev/null 2>&1 &');
            $this->line('Starting ' . $room->id() . ' listener.');
        }
        return $this;
    }
}
