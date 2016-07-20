<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @author butschster <butschster@gmail.com>
 *
 * @date 24.09.2015 15:27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Console\Commands;


use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Interfaces\Gitter\Room\RoomInterface;

/**
 * Class StartGitterBot
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
     * @param Container $app
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \Exception
     */
    public function handle(Container $app)
    {
        $roomId = $this->argument('room');

        /** @var RoomInterface $room */
        if (is_null($room = $app['room.manager']->get($roomId))) {
            $this->warn("Room [$roomId] not found");

            return;
        }

        $client = $room->client();
        $room->listen();

        $this->info(sprintf('%s started at %s', $client->version(), Carbon::now()));

        $this->makePidFile();
        $client->run();

        $this->removePidFile();
    }

    /**
     * Create pid file
     */
    protected function makePidFile()
    {
        $this->pid = storage_path('pids/' . date('Y_m_d_tis_') . microtime(1) . '.pid');
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
